<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuctionItem extends Model
{
    protected $fillable = [
        'tournament_id', 'player_id', 'team_id',
        'base_price', 'final_price', 'status', 'bid_count'
    ];

    public function tournament() { return $this->belongsTo(Tournament::class); }
    public function player()     { return $this->belongsTo(Player::class); }
    public function team()       { return $this->belongsTo(Team::class); }
}