<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'body' => $this->body,
            'user_id' => $this->user_id,
            'user' => UserResource::make($this->whenLoaded('user')),
            'attachment_path' => $this->attachment_path,
            'attachment_name' => $this->attachment_name,
            'created_at' => $this->created_at,
            'reads' => $this->whenLoaded('reads'),
        ];
    }
}
