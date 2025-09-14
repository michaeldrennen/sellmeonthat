<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * Get the parent category of this category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories of this category.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * The business profiles that belong to this category.
     */
    public function businessProfiles(): BelongsToMany
    {
        return $this->belongsToMany(BusinessProfile::class);
    }

    /**
     * The wants that belong to this category.
     */
    public function wants(): BelongsToMany
    {
        return $this->belongsToMany(Want::class);
    }
}