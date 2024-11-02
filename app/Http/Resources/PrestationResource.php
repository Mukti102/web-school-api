<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrestationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status_winner' => $this->status_winner,
            'id' => $this->id,
            'author' => $this->author,
            'waktu' => Carbon::parse($this->waktu)->format('H:i'),
            'title' => $this->title,
            'description' => $this->description,
            'date' => Carbon::parse($this->date)->format('d F Y'),
            'juara' => $this->juara,
            'penyelenggara' => $this->penyelenggara,
            'thumbnail' => env('ENDPOINT') . 'storage/' . $this->thumbnail,
            'created_at' => Carbon::parse($this->created_at)->format('d F Y'),
        ];
    }
}
