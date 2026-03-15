<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TournamentController extends Controller
{
    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function create()
    {
        return view('tournaments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|min:3|max:100',
            'description'     => 'nullable|string|max:500',
            'total_teams'     => 'required|integer|min:2|max:32',
            'budget_per_team' => 'required|numeric|min:1000',
            'max_squad_size'  => 'required|integer|min:5|max:30',
            'auction_mode'    => 'required|in:manual,live,both',
        ], [
            'name.required'            => 'Tournament name is required.',
            'name.min'                 => 'Name must be at least 3 characters.',
            'total_teams.required'     => 'Number of teams is required.',
            'total_teams.min'          => 'Minimum 2 teams required.',
            'total_teams.max'          => 'Maximum 32 teams allowed.',
            'budget_per_team.required' => 'Budget per team is required.',
            'budget_per_team.min'      => 'Minimum budget is ₹1,000.',
            'max_squad_size.required'  => 'Squad size is required.',
            'max_squad_size.min'       => 'Minimum squad size is 5.',
            'max_squad_size.max'       => 'Maximum squad size is 30.',
            'auction_mode.required'    => 'Please select an auction mode.',
        ]);

        // Generate unique slug
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (Tournament::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $tournament = Tournament::create([
            'user_id'         => Auth::id(),
            'name'            => $request->name,
            'description'     => $request->description,
            'sport'           => 'cricket',
            'total_teams'     => $request->total_teams,
            'budget_per_team' => $request->budget_per_team,
            'max_squad_size'  => $request->max_squad_size,
            'auction_mode'    => $request->auction_mode,
            'status'          => 'draft',
            'registration_open' => false,
            'slug'            => $slug,
        ]);

        return redirect()
            ->route('tournaments.show', $tournament->id)
            ->with('success', 'Tournament created successfully! 🎉 Now add your teams.');
    }

    public function show(Tournament $tournament)
    {
        $this->authorize_owner($tournament);

        $teams   = $tournament->teams()->withCount('players')->get();
        $players = $tournament->players()->latest()->get();

        $stats = [
            'total_teams'     => $teams->count(),
            'total_players'   => $players->count(),
            'sold_players'    => $players->where('status', 'sold')->count(),
            'total_spent'     => $teams->sum('spent'),
        ];

        return view('tournaments.show', compact('tournament', 'teams', 'players', 'stats'));
    }

    public function edit(Tournament $tournament)
    {
        $this->authorize_owner($tournament);
        return view('tournaments.create', compact('tournament'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        $this->authorize_owner($tournament);

        $request->validate([
            'name'            => 'required|string|min:3|max:100',
            'description'     => 'nullable|string|max:500',
            'total_teams'     => 'required|integer|min:2|max:32',
            'budget_per_team' => 'required|numeric|min:1000',
            'max_squad_size'  => 'required|integer|min:5|max:30',
            'auction_mode'    => 'required|in:manual,live,both',
        ]);

        $tournament->update([
            'name'            => $request->name,
            'description'     => $request->description,
            'total_teams'     => $request->total_teams,
            'budget_per_team' => $request->budget_per_team,
            'max_squad_size'  => $request->max_squad_size,
            'auction_mode'    => $request->auction_mode,
        ]);

        return redirect()
            ->route('tournaments.show', $tournament->id)
            ->with('success', 'Tournament updated successfully!');
    }

    public function destroy(Tournament $tournament)
    {
        $this->authorize_owner($tournament);
        $tournament->delete();
        return redirect()->route('dashboard')->with('success', 'Tournament deleted.');
    }

    public function public($slug)
    {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();
        return view('tournaments.public', compact('tournament'));
    }

    // Toggle registration
    public function toggleRegistration(Tournament $tournament)
    {
        $this->authorize_owner($tournament);
        $tournament->update(['registration_open' => !$tournament->registration_open]);
        $msg = $tournament->registration_open ? 'Registration opened!' : 'Registration closed.';
        return back()->with('success', $msg);
    }

    // Update status
    public function updateStatus(Request $request, Tournament $tournament)
    {
        $this->authorize_owner($tournament);
        $request->validate(['status' => 'required|in:draft,active,auction,completed']);
        $tournament->update(['status' => $request->status]);
        return back()->with('success', 'Tournament status updated!');
    }

    private function authorize_owner(Tournament $tournament)
    {
        if ($tournament->user_id !== Auth::id()) {
            abort(403, 'Unauthorized.');
        }
    }
}