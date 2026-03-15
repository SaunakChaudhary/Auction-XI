<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Tournament;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $tournaments = Tournament::where('user_id', $user->id)
                        ->withCount(['teams', 'players'])
                        ->latest()
                        ->get();

        $stats = [
            'total_tournaments' => $tournaments->count(),
            'active_tournaments' => $tournaments->where('status', 'active')->count(),
            'total_teams'        => $tournaments->sum('teams_count'),
            'total_players'      => $tournaments->sum('players_count'),
        ];

        return view('dashboard.index', compact('tournaments', 'stats'));
    }
}