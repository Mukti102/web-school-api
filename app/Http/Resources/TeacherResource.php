<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
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
            'NUPTK' => $this->NUPTK,
            'NIP' => $this->NIP,
            'start' => $this->start,
            'TTL' => $this->TTL,
            'religion' => $this->religion,
            'gender' => $this->gender,
            'status' => $this->status,
            'email' => $this->email,
            'phone' => $this->phone,
            'position' => $this->position,
            'address' => $this->address,
            'photo' => $this->photo,
        ];
    }
}
