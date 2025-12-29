<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'contact_email',
        'contact_phone',
        'website',
    ];

    /**
     * Get all courts for this partner
     */
    public function courts(): HasMany
    {
        return $this->hasMany(Court::class);
    }
}
