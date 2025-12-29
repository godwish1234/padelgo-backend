<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Set extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'match_id',
        'set_number',
        'team_a_games',
        'team_b_games',
        'is_completed',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
        ];
    }

    /**
     * Get the match
     */
    public function match(): BelongsTo
    {
        return $this->belongsTo(PadelMatch::class);
    }

    /**
     * Get all games in this set
     */
    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }
}
