<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Tournament $tournament)
    {
        $teams = $tournament->teams()->withCount('players')->get();
        return view('teams.index', compact('tournament', 'teams'));
    }

    public function create(Tournament $tournament)
    {
        return view('teams.create', compact('tournament'));
    }

    public function store(Request $request, Tournament $tournament)
    {
        // Build next
    }

    public function show(Tournament $tournament, Team $team)
    {
        return view('teams.show', compact('tournament', 'team'));
    }

    public function destroy(Tournament $tournament, Team $team)
    {
        $team->delete();
        return back()->with('success', 'Team deleted.');
    }
}