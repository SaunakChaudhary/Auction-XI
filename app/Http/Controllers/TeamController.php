<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index(Tournament $tournament)
    {
        $this->authorizeOwner($tournament);
        $teams = $tournament->teams()->withCount('players')->get();
        return view('teams.index', compact('tournament', 'teams'));
    }

    public function create(Tournament $tournament)
    {
        $this->authorizeOwner($tournament);

        // Check if max teams reached
        if ($tournament->teams()->count() >= $tournament->total_teams) {
            return redirect()
                ->route('tournaments.show', $tournament->id)
                ->with('error', 'Maximum teams ('.$tournament->total_teams.') already created.');
        }

        return view('teams.create', compact('tournament'));
    }

    public function store(Request $request, Tournament $tournament)
    {
        $this->authorizeOwner($tournament);

        // Max teams validation
        if ($tournament->teams()->count() >= $tournament->total_teams) {
            return back()->with('error', 'Maximum number of teams already reached.');
        }

        $request->validate([
            'name'       => 'required|string|min:2|max:100',
            'short_name' => 'required|string|min:2|max:6|alpha',
            'color'      => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'owner_name' => 'nullable|string|max:100',
            'owner_phone'=> 'nullable|digits:10',
        ], [
            'name.required'        => 'Team name is required.',
            'name.min'             => 'Team name must be at least 2 characters.',
            'short_name.required'  => 'Short name is required (e.g. MI, CSK).',
            'short_name.max'       => 'Short name cannot exceed 6 characters.',
            'short_name.alpha'     => 'Short name must contain only letters.',
            'color.required'       => 'Please pick a team color.',
            'color.regex'          => 'Invalid color format.',
            'owner_phone.digits'   => 'Phone number must be 10 digits.',
        ]);

        // Check duplicate name in same tournament
        $exists = $tournament->teams()
                    ->whereRaw('LOWER(name) = ?', [strtolower($request->name)])
                    ->exists();
        if ($exists) {
            return back()->withInput()
                ->withErrors(['name' => 'A team with this name already exists in this tournament.']);
        }

        // Check duplicate short name
        $shortExists = $tournament->teams()
                    ->whereRaw('LOWER(short_name) = ?', [strtolower($request->short_name)])
                    ->exists();
        if ($shortExists) {
            return back()->withInput()
                ->withErrors(['short_name' => 'This short name is already used in this tournament.']);
        }

        Team::create([
            'tournament_id' => $tournament->id,
            'user_id'       => Auth::id(),
            'name'          => $request->name,
            'short_name'    => strtoupper($request->short_name),
            'color'         => $request->color,
            'budget'        => $tournament->budget_per_team,
            'spent'         => 0,
        ]);

        // Check if all teams created
        $teamsCreated = $tournament->teams()->count();
        if ($teamsCreated >= $tournament->total_teams) {
            return redirect()
                ->route('tournaments.show', $tournament->id)
                ->with('success', 'All ' . $tournament->total_teams . ' teams created! 🎉 You can now open player registration.');
        }

        $remaining = $tournament->total_teams - $teamsCreated;

        // If user wants to add more
        if ($request->has('add_another')) {
            return redirect()
                ->route('tournaments.teams.create', $tournament->id)
                ->with('success', 'Team added! ' . $remaining . ' more team(s) to add.');
        }

        return redirect()
            ->route('tournaments.show', $tournament->id)
            ->with('success', 'Team created successfully! ' . $remaining . ' more team(s) needed.');
    }

    public function show(Tournament $tournament, Team $team)
    {
        $this->authorizeOwner($tournament);
        $players = $team->players()->get();
        return view('teams.show', compact('tournament', 'team', 'players'));
    }

    public function edit(Tournament $tournament, Team $team)
    {
        $this->authorizeOwner($tournament);
        return view('teams.create', compact('tournament', 'team'));
    }

    public function update(Request $request, Tournament $tournament, Team $team)
    {
        $this->authorizeOwner($tournament);

        $request->validate([
            'name'       => 'required|string|min:2|max:100',
            'short_name' => 'required|string|min:2|max:6|alpha',
            'color'      => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        // Duplicate check excluding current team
        $exists = $tournament->teams()
                    ->whereRaw('LOWER(name) = ?', [strtolower($request->name)])
                    ->where('id', '!=', $team->id)
                    ->exists();
        if ($exists) {
            return back()->withInput()
                ->withErrors(['name' => 'A team with this name already exists.']);
        }

        $team->update([
            'name'       => $request->name,
            'short_name' => strtoupper($request->short_name),
            'color'      => $request->color,
        ]);

        return redirect()
            ->route('tournaments.show', $tournament->id)
            ->with('success', 'Team updated successfully!');
    }

    public function destroy(Tournament $tournament, Team $team)
    {
        $this->authorizeOwner($tournament);
        $team->delete();
        return back()->with('success', 'Team deleted.');
    }

    private function authorizeOwner(Tournament $tournament)
    {
        if ($tournament->user_id !== Auth::id()) {
            abort(403, 'Unauthorized.');
        }
    }
}