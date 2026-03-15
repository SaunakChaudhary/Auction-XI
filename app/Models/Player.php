<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'tournament_id', 'team_id', 'name', 'phone', 'email',
        'age', 'city', 'batting_style', 'bowling_style',
        'role', 'photo', 'base_price', 'sold_price', 'status'
    ];

    public function tournament() { return $this->belongsTo(Tournament::class); }
    public function team()       { return $this->belongsTo(Team::class); }

    public function getRoleLabelAttribute()
    {
        return match($this->role) {
            'batsman'        => 'Batsman',
            'bowler'         => 'Bowler',
            'all_rounder'    => 'All-rounder',
            'wicket_keeper'  => 'Wicket Keeper',
            default          => 'Player',
        };
    }
}