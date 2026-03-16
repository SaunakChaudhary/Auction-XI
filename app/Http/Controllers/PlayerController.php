<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlayerController extends Controller
{
    public function index(Tournament $tournament)
    {
        $this->authorizeOwner($tournament);

        $players = $tournament->players()
                    ->with('team')
                    ->latest()
                    ->get();

        $stats = [
            'total'      => $players->count(),
            'registered' => $players->where('status', 'registered')->count(),
            'available'  => $players->where('status', 'available')->count(),
            'sold'       => $players->where('status', 'sold')->count(),
            'unsold'     => $players->where('status', 'unsold')->count(),
        ];

        return view('players.index', compact('tournament', 'players', 'stats'));
    }

    public function showForm($slug)
    {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();

        if (!$tournament->registration_open) {
            return view('players.registration-closed', compact('tournament'));
        }

        return view('players.register', compact('tournament'));
    }

    public function register(Request $request, $slug)
    {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();

        if (!$tournament->registration_open) {
            return back()->with('error', 'Registration is currently closed.');
        }

        $request->validate([
            'name'          => 'required|string|min:2|max:100',
            'phone'         => 'required|digits:10',
            'email'         => 'nullable|email|max:150',
            'age'           => 'required|integer|min:10|max:60',
            'city'          => 'required|string|max:100',
            'role'          => 'required|in:batsman,bowler,all_rounder,wicket_keeper',
            'batting_style' => 'required|in:right_hand,left_hand',
            'bowling_style' => 'required|in:right_arm_fast,right_arm_spin,left_arm_fast,left_arm_spin,none',
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required'          => 'Full name is required.',
            'name.min'               => 'Name must be at least 2 characters.',
            'phone.required'         => 'Phone number is required.',
            'phone.digits'           => 'Phone number must be exactly 10 digits.',
            'age.required'           => 'Age is required.',
            'age.min'                => 'Minimum age is 10.',
            'age.max'                => 'Maximum age is 60.',
            'city.required'          => 'City is required.',
            'role.required'          => 'Please select your playing role.',
            'batting_style.required' => 'Please select batting style.',
            'bowling_style.required' => 'Please select bowling style.',
            'photo.image'            => 'Photo must be an image file.',
            'photo.max'              => 'Photo size must be under 2MB.',
        ]);

        // Check duplicate phone in same tournament
        $exists = $tournament->players()
                    ->where('phone', $request->phone)
                    ->exists();
        if ($exists) {
            return back()->withInput()
                ->withErrors(['phone' => 'This phone number is already registered in this tournament.']);
        }

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')
                ->store('players/photos', 'public');
        }

        Player::create([
            'tournament_id'  => $tournament->id,
            'name'           => $request->name,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'age'            => $request->age,
            'city'           => $request->city,
            'role'           => $request->role,
            'batting_style'  => $request->batting_style,
            'bowling_style'  => $request->bowling_style,
            'photo'          => $photoPath,
            'status'         => 'registered',
            'base_price'     => 0,
        ]);

        return redirect()
            ->route('player.register.form', $slug)
            ->with('success', 'Registration successful! 🎉 You have been registered for ' . $tournament->name);
    }

    public function updateBasePrice(Request $request, Tournament $tournament, Player $player)
    {
        $this->authorizeOwner($tournament);

        $request->validate([
            'base_price' => 'required|numeric|min:0',
        ]);

        $player->update([
            'base_price' => $request->base_price,
            'status'     => 'available',
        ]);

        return back()->with('success', 'Base price updated for ' . $player->name);
    }

    public function bulkUpdateStatus(Request $request, Tournament $tournament)
    {
        $this->authorizeOwner($tournament);

        $request->validate([
            'player_ids'   => 'required|array',
            'player_ids.*' => 'exists:players,id',
            'status'       => 'required|in:registered,available,unsold',
        ]);

        Player::whereIn('id', $request->player_ids)
            ->where('tournament_id', $tournament->id)
            ->update(['status' => $request->status]);

        return back()->with('success', count($request->player_ids) . ' players updated.');
    }

    public function destroy(Tournament $tournament, Player $player)
    {
        $this->authorizeOwner($tournament);

        if ($player->photo) {
            Storage::disk('public')->delete($player->photo);
        }

        $player->delete();
        return back()->with('success', 'Player removed.');
    }

    private function authorizeOwner(Tournament $tournament)
    {
        if ($tournament->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }
    }
}