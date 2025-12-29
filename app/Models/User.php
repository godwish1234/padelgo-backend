<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    /**
     * Get all matches created by this user
     */
    public function createdMatches(): HasMany
    {
        return $this->hasMany(PadelMatch::class, 'creator_id');
    }

    /**
     * Get all matches this user has joined
     */
    public function joinedMatches()
    {
        return $this->belongsToMany(PadelMatch::class, 'match_players', 'user_id', 'match_id')
            ->withPivot(['team', 'status'])
            ->withTimestamps();
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role->isAdmin();
    }

    /**
     * Check if user is a court admin
     */
    public function isCourtAdmin(): bool
    {
        return $this->role === UserRole::COURT_ADMIN;
    }

    /**
     * Get courts managed by this user (if court admin)
     */
    public function managedCourts(): HasMany
    {
        return $this->hasMany(Court::class, 'admin_user_id');
    }
}
