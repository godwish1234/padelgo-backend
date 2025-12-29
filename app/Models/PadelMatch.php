<?php

namespace App\Models;

use App\Enums\MatchStatus;
use App\Enums\MatchType;
use App\Enums\SkillLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PadelMatch extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'court_id',
        'creator_id',
        'title',
        'description',
        'match_date_time',
        'max_players',
        'current_players',
        'skill_level',
        'match_type',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'match_date_time' => 'datetime',
            'skill_level' => SkillLevel::class,
            'match_type' => MatchType::class,
            'status' => MatchStatus::class,
        ];
    }

    /**
     * Get the court where this match is played
     */
    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    /**
     * Get the user who created this match
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get all players in this match
     */
    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'match_players', 'match_id', 'user_id')
            ->withPivot(['team', 'status', 'joined_at'])
            ->withTimestamps();
    }

    /**
     * Get all match players (pivot records)
     */
    public function matchPlayers(): HasMany
    {
        return $this->hasMany(MatchPlayer::class);
    }

    /**
     * Get all sets in this match
     */
    public function sets(): HasMany
    {
        return $this->hasMany(Set::class);
    }

    /**
     * Check if match is full
     */
    public function isFull(): bool
    {
        return $this->current_players >= $this->max_players;
    }

    /**
     * Check if user can join this match
     */
    public function canUserJoin(User $user): bool
    {
        if ($this->isFull()) {
            return false;
        }

        if ($this->creator_id === $user->id) {
            return false;
        }

        return !$this->players()->where('user_id', $user->id)->exists();
    }

    /**
     * Add a player to the match
     */
    public function addPlayer(User $user, ?string $team = null): MatchPlayer
    {
        return $this->matchPlayers()->create([
            'user_id' => $user->id,
            'team' => $team,
            'status' => 'joined',
        ]);
    }

    /**
     * Remove a player from the match
     */
    public function removePlayer(User $user): bool
    {
        return $this->matchPlayers()
            ->where('user_id', $user->id)
            ->delete() > 0;
    }
}
