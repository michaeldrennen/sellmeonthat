<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'budget_min' => $this->budget_min,
            'budget_max' => $this->budget_max,
            'status' => $this->status,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zip_code,
            'country' => $this->country,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'radius_miles' => $this->radius_miles,
            'image_paths' => $this->image_paths ? array_map(fn($path) => asset('storage/' . $path), $this->image_paths) : [],
            'is_draft' => $this->is_draft,
            'expires_at' => $this->expires_at?->toISOString(),
            'published_at' => $this->published_at?->toISOString(),
            'user' => new UserResource($this->whenLoaded('user')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'offers' => OfferResource::collection($this->whenLoaded('offers')),
            'offers_count' => $this->when(isset($this->offers_count), $this->offers_count),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
