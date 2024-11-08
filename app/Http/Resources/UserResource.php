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
            'pendaftaran' => $this->whenLoaded('student'),
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'profile' => env('ENDPOINT') . 'storage/' . $this->profile,
            'role' => $this->role, // Default role is 'user' if not provided
        ];
    }
}
