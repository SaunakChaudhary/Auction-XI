<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Player;
use App\Models\Team;
use App\Models\AuctionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AuctionController extends Controller
{
    // ── Creator Panel ──────────────────────────────────────────
    public function index(Tournament $tournament)
    {
        $this->authorizeOwner($tournament);

        $teams   = $tournament->teams()->withCount('players')->get();
        $players = $tournament->players()->with('team')->get();

        $stats = [
            'total_players' => $players->count(),
            'available'     => $players->whereIn('status', ['available','registered'])->count(),
            'sold'          => $players->where('status', 'sold')->count(),
            'unsold'        => $players->where('status', 'unsold')->count(),
            'total_spent'   => $teams->sum('spent'),
            'total_budget'  => $teams->sum('budget'),
        ];

        return view('auction.index',
            compact('tournament', 'teams', 'players', 'stats'));
    }

    // ── Search player by ID (AJAX) ──────────────────────────────
    public function searchPlayer(Request $request, Tournament $tournament)
    {
        $this->authorizeOwner($tournament);

        $request->validate(['player_id' => 'required|integer']);

        $player = Player::where('id', $request->player_id)
                    ->where('tournament_id', $tournament->id)
                    ->with('team')
                    ->first();

        if (!$player) {
            return response()->json([
                'success' => false,
                'message' => 'Player not found with ID #' . $request->player_id,
            ]);
        }

        return response()->json([
            'success' => true,
            'player'  => [
                'id'            => $player->id,
                'name'          => $player->name,
                'phone'         => $player->phone,
                'age'           => $player->age,
                'city'          => $player->city,
                'role'          => $player->roleLabel,
                'batting_style' => ucwords(str_replace('_', ' ', $player->batting_style ?? '')),
                'bowling_style' => ucwords(str_replace('_', ' ', $player->bowling_style ?? '')),
                'base_price'    => $player->base_price,
                'status'        => $player->status,
                'photo'         => $player->photo ? Storage::url($player->photo) : null,
                'team'          => $player->team
                                    ? ['name' => $player->team->name, 'color' => $player->team->color]
                                    : null,
                'initials'      => strtoupper(substr($player->name, 0, 2)),
            ],
        ]);
    }

    // ── Set spotlight ───────────────────────────────────────────
    public function setSpotlight(Request $request, Tournament $tournament)
    {
        $this->authorizeOwner($tournament);
        $request->validate(['player_id' => 'required|exists:players,id']);

        $player = Player::where('id', $request->player_id)
                    ->where('tournament_id', $tournament->id)
                    ->firstOrFail();

        cache()->put("auction_spotlight_{$tournament->id}", $player->id, 3600);

        return response()->json(['success' => true, 'player_name' => $player->name]);
    }

    // ── Sell player ─────────────────────────────────────────────
    public function sellPlayer(Request $request, Tournament $tournament)
    {
        $this->authorizeOwner($tournament);

        $request->validate([
            'player_id'  => 'required|exists:players,id',
            'team_id'    => 'required|exists:teams,id',
            'sold_price' => 'required|numeric|min:0',
        ]);

        $player = Player::findOrFail($request->player_id);
        $team   = Team::findOrFail($request->team_id);

        if ($player->tournament_id !== $tournament->id)
            return back()->with('error', 'Invalid player.');
        if ($team->tournament_id !== $tournament->id)
            return back()->with('error', 'Invalid team.');
        if ($player->status === 'sold')
            return back()->with('error', $player->name . ' is already sold.');

        $remaining = $team->budget - $team->spent;
        if ($request->sold_price > $remaining)
            return back()->with('error',
                'Insufficient budget! ' . $team->name .
                ' only has ₹' . number_format($remaining) . ' remaining.');

        if ($team->players()->count() >= $tournament->max_squad_size)
            return back()->with('error',
                $team->name . ' squad is full! (' . $tournament->max_squad_size . ' max)');

        if ($request->sold_price < $player->base_price)
            return back()->with('error',
                'Sold price cannot be less than base price ₹' .
                number_format($player->base_price) . '.');

        DB::transaction(function () use ($player, $team, $request, $tournament) {
            $player->update([
                'team_id'    => $team->id,
                'sold_price' => $request->sold_price,
                'status'     => 'sold',
            ]);
            $team->increment('spent', $request->sold_price);
            AuctionItem::updateOrCreate(
                ['tournament_id' => $tournament->id, 'player_id' => $player->id],
                [
                    'team_id'     => $team->id,
                    'base_price'  => $player->base_price,
                    'final_price' => $request->sold_price,
                    'status'      => 'sold',
                ]
            );
        });

        return back()->with('success',
            $player->name . ' sold to ' . $team->name .
            ' for ₹' . number_format($request->sold_price) . '! 🎉');
    }

    // ── Mark Unsold ─────────────────────────────────────────────
    public function markUnsold(Request $request, Tournament $tournament)
    {
        $this->authorizeOwner($tournament);
        $request->validate(['player_id' => 'required|exists:players,id']);

        $player = Player::where('id', $request->player_id)
                    ->where('tournament_id', $tournament->id)
                    ->firstOrFail();

        $player->update(['status' => 'unsold', 'team_id' => null, 'sold_price' => null]);
        AuctionItem::updateOrCreate(
            ['tournament_id' => $tournament->id, 'player_id' => $player->id],
            ['status' => 'unsold', 'team_id' => null, 'final_price' => null]
        );

        return back()->with('success', $player->name . ' marked as unsold.');
    }

    // ── Undo Sell ───────────────────────────────────────────────
    public function undoSell(Request $request, Tournament $tournament)
    {
        $this->authorizeOwner($tournament);
        $request->validate(['player_id' => 'required|exists:players,id']);

        $player = Player::where('id', $request->player_id)
                    ->where('tournament_id', $tournament->id)
                    ->firstOrFail();

        if ($player->status !== 'sold')
            return back()->with('error', 'Player is not sold.');

        DB::transaction(function () use ($player, $tournament) {
            if ($player->team_id && $player->sold_price)
                Team::where('id', $player->team_id)
                    ->decrement('spent', $player->sold_price);

            $player->update([
                'team_id'    => null,
                'sold_price' => null,
                'status'     => 'available',
            ]);

            AuctionItem::where('tournament_id', $tournament->id)
                ->where('player_id', $player->id)
                ->delete();
        });

        return back()->with('success', 'Sale undone. Budget refunded.');
    }

    // ── Public Viewer Room ───────────────────────────────────────
    public function room($slug)
    {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();

        $teams   = $tournament->teams()
                    ->with(['players' => fn($q) => $q->where('status', 'sold')])
                    ->get();
        $players = $tournament->players()->with('team')->latest()->get();

        $spotlightId = cache()->get("auction_spotlight_{$tournament->id}");
        $spotlight   = $spotlightId
                        ? Player::with('team')->find($spotlightId)
                        : null;

        return view('auction.room',
            compact('tournament', 'teams', 'players', 'spotlight'));
    }

    // ── API: Polling endpoint ────────────────────────────────────
    public function state(Tournament $tournament)
    {
        $teams   = $tournament->teams()->withCount('players')->get();
        $players = $tournament->players()->with('team')->get();

        $spotlightId = cache()->get("auction_spotlight_{$tournament->id}");
        $spotlight   = $spotlightId
                        ? Player::with('team')->find($spotlightId)
                        : null;

        return response()->json([
            'teams'   => $teams->map(fn($t) => [
                'id'             => $t->id,
                'name'           => $t->name,
                'short_name'     => $t->short_name,
                'color'          => $t->color,
                'budget'         => $t->budget,
                'spent'          => $t->spent,
                'remaining'      => $t->budget - $t->spent,
                'players_count'  => $t->players_count,
            ]),
            'players' => $players->map(fn($p) => [
                'id'         => $p->id,
                'name'       => $p->name,
                'role'       => $p->roleLabel,
                'city'       => $p->city,
                'age'        => $p->age,
                'status'     => $p->status,
                'base_price' => $p->base_price,
                'sold_price' => $p->sold_price,
                'photo'      => $p->photo ? Storage::url($p->photo) : null,
                'initials'   => strtoupper(substr($p->name, 0, 2)),
                'team'       => $p->team ? [
                    'name'       => $p->team->name,
                    'short_name' => $p->team->short_name,
                    'color'      => $p->team->color,
                ] : null,
            ]),
            'spotlight' => $spotlight ? [
                'id'         => $spotlight->id,
                'name'       => $spotlight->name,
                'role'       => $spotlight->roleLabel,
                'city'       => $spotlight->city,
                'age'        => $spotlight->age,
                'base_price' => $spotlight->base_price,
                'sold_price' => $spotlight->sold_price,
                'status'     => $spotlight->status,
                'photo'      => $spotlight->photo
                                ? Storage::url($spotlight->photo) : null,
                'initials'   => strtoupper(substr($spotlight->name, 0, 2)),
                'team'       => $spotlight->team ? [
                    'name'  => $spotlight->team->name,
                    'color' => $spotlight->team->color,
                ] : null,
            ] : null,
            'stats' => [
                'total'     => $players->count(),
                'sold'      => $players->where('status', 'sold')->count(),
                'unsold'    => $players->where('status', 'unsold')->count(),
                'available' => $players->whereIn('status', ['available','registered'])->count(),
            ],
            'timestamp' => now()->timestamp,
        ]);
    }

    // ── Results ──────────────────────────────────────────────────
    public function results(Tournament $tournament)
    {
        $this->authorizeOwner($tournament);

        $teams = $tournament->teams()->with([
            'players' => fn($q) => $q->where('status', 'sold')->orderByDesc('sold_price'),
        ])->get();

        $unsoldPlayers = $tournament->players()->where('status', 'unsold')->get();

        return view('auction.results', compact('tournament', 'teams', 'unsoldPlayers'));
    }

    public function live(Tournament $tournament)
    {
        $this->authorizeOwner($tournament);
        return view('auction.live', compact('tournament'));
    }

    private function authorizeOwner(Tournament $tournament): void
    {
        if ($tournament->user_id !== Auth::id()) abort(403);
    }
}