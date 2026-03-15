<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'sport',
        'total_teams',
        'budget_per_team',
        'max_squad_size',
        'auction_mode',
        'status',
        'registration_open',
        'slug',
    ];

    protected $casts = [
        'registration_open' => 'boolean',
        'budget_per_team'   => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function auctionItems()
    {
        return $this->hasMany(AuctionItem::class);
    }

    // Status badge helper
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'draft'     => ['label' => 'Draft',     'class' => 'badge-draft'],
            'active'    => ['label' => 'Active',    'class' => 'badge-active'],
            'auction'   => ['label' => 'Auction',   'class' => 'badge-auction'],
            'completed' => ['label' => 'Completed', 'class' => 'badge-completed'],
            default     => ['label' => 'Draft',     'class' => 'badge-draft'],
        };
    }

    // Budget formatter
    public function getFormattedBudgetAttribute()
    {
        $budget = $this->budget_per_team;
        if ($budget >= 100000) return '₹' . number_format($budget / 100000, 1) . 'L';
        if ($budget >= 1000)   return '₹' . number_format($budget / 1000, 0) . 'K';
        return '₹' . number_format($budget, 0);
    }
}