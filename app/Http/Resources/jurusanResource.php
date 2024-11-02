<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class jurusanResource extends JsonResource
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
            'author' => $this->author,
            'title' => $this->title,
            'description' => $this->description,
            'thumbnail' => env('ENDPOINT') . 'storage/' . $this->thumbnail,
            'galery' => $this->whenLoaded('jurusanGalery'),
            'created_at' => Carbon::parse($this->created_at)->format('d F Y'),
        ];
    }
}
