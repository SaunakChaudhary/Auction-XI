<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'tournament_id', 'user_id', 'name',
        'short_name', 'color', 'logo', 'budget', 'spent'
    ];

    public function tournament() { return $this->belongsTo(Tournament::class); }
    public function players()    { return $this->hasMany(Player::class); }

    public function getRemainingBudgetAttribute()
    {
        return $this->budget - $this->spent;
    }
}
