<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'want_id',
        'business_profile_id',
        'price',
        'message',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the want that this offer is for.
     */
    public function want(): BelongsTo
    {
        return $this->belongsTo(Want::class);
    }

    /**
     * Get the business profile that made this offer.
     */
    public function businessProfile(): BelongsTo
    {
        return $this->belongsTo(BusinessProfile::class);
    }

    /**
     * Scope to get pending offers.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get accepted offers.
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }
}