<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'want_id' => $this->want_id,
            'price' => $this->price,
            'message' => $this->message,
            'status' => $this->status,
            'business_profile' => new BusinessProfileResource($this->whenLoaded('businessProfile')),
            'want' => new WantResource($this->whenLoaded('want')),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
