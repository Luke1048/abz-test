<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'position' => (new PositionResource($this->whenLoaded('position')))->getAttributes()['name'],
            'position_id' => $this->position_id,
            'registration_timestamp' => $this->registration_timestamp,
            'photo' => $this->photo,
        ];
    }
}
