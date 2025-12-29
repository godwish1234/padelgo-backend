<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatchPlayer extends Model
{
    use HasFactory;

    protected $table = 'match_players';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'match_id',
        'user_id',
        'team',
        'status',
    ];

    /**
     * Get the match
     */
    public function match(): BelongsTo
    {
        return $this->belongsTo(PadelMatch::class);
    }

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
