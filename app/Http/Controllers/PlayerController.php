<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index(Tournament $tournament)
    {
        $players = $tournament->players()->latest()->get();
        return view('players.index', compact('tournament', 'players'));
    }

    public function showForm($slug)
    {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();
        return view('players.register', compact('tournament'));
    }

    public function register(Request $request, $slug)
    {
        // Build next
    }

    public function destroy(Tournament $tournament, Player $player)
    {
        $player->delete();
        return back()->with('success', 'Player removed.');
    }
}