<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    use HasFactory;

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
}