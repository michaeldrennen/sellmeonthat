<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Want extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'budget_min',
        'budget_max',
        'status',
        'expires_at',
        'city',
        'state',
        'zip_code',
        'country',
        'latitude',
        'longitude',
        'radius_miles',
        'image_paths',
        'is_draft',
        'published_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'published_at' => 'datetime',
        'image_paths' => 'array',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_draft' => 'boolean',
    ];

    /**
     * Get the user who created the want.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the offers for the want.
     */
    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    /**
     * The categories that this want belongs to.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Get the conversations for this want.
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    /**
     * Scope to get only published wants.
     */
    public function scopePublished($query)
    {
        return $query->where('is_draft', false)
                     ->whereNotNull('published_at');
    }

    /**
     * Scope to get open wants.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }
}