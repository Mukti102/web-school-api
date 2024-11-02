<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
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
            'video_path' => $this->video_path,
            'thumbnail' => env('ENDPOINT') . 'storage/' . $this->thumbnail,
            // 'thumbnail' => $this->thumbnail,
            'created_at' => Carbon::parse($this->created_at)->format('d F Y'),
        ];
    }
}
