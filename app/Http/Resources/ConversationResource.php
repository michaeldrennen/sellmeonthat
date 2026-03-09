<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
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
            'user' => new UserResource($this->whenLoaded('user')),
            'business_profile' => new BusinessProfileResource($this->whenLoaded('businessProfile')),
            'want' => new WantResource($this->whenLoaded('want')),
            'last_message_at' => $this->last_message_at?->toISOString(),
            'latest_message' => new MessageResource($this->whenLoaded('latestMessage')),
            'unread_count' => $this->when(isset($this->unread_count), $this->unread_count),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
