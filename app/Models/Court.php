<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Court extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'partner_location_id',
        'partner_id',
        'admin_user_id',
        'name',
        'address',
        'city',
        'latitude',
        'longitude',
        'phone',
        'facilities',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'facilities' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the partner location that owns this court
     */
    public function partnerLocation(): BelongsTo
    {
        return $this->belongsTo(PartnerLocation::class);
    }

    /**
     * Get the partner that owns this court
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Get the admin user for this court
     */
    public function adminUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    /**
     * Get all schedules for this court
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(CourtSchedule::class);
    }

    /**
     * Get only active schedules
     */
    public function activeSchedules(): HasMany
    {
        return $this->hasMany(CourtSchedule::class)->where('is_active', true);
    }

    /**
     * Get all matches at this court
     */
    public function matches(): HasMany
    {
        return $this->hasMany(PadelMatch::class);
    }

    /**
     * Scope to get only active courts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
