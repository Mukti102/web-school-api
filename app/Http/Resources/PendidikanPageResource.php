<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PendidikanPageResource extends JsonResource
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
            'title' =>  $this->title,
            'description' =>  $this->description,
            'potret_photo' => env('ENDPOINT') . 'storage/' . $this->potret_photo,
            'lanscape_photo' => env('ENDPOINT') . 'storage/' . $this->lanscape_photo,
            'thumbnail' => env('ENDPOINT') . 'storage/' . $this->thumbnail,
        ];
    }
}
