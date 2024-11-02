<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncmentResource extends JsonResource
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
            'author' => $this->author,
            'description' => $this->description,
            'date' => $this->date,
            'time' => $this->time,
            'lampiran' => $this->lampiran,
            'thumbnail' => env('ENDPOINT') . 'storage/' . $this->thumbnail,
            'created_at' => Carbon::parse($this->created_at)->format('d F Y'),
        ];
    }
}
