<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'user' => $this->whenLoaded('user'),
            'nomor_registrasi' => $this->nomor_registrasi,
            'angkatan' => $this->whenLoaded('angkatan'),
            'fullname' => $this->fullname,
            'NISN' => $this->NISN,
            'NIS' => $this->NIS,
            'gender' => $this->gender,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'agama' => $this->agama,
            'anak_ke' => $this->anak_ke,
            'jumlah_saudara' => $this->jumlah_saudara,
            'no_hp' => $this->no_hp,
            'email' => $this->email,
            'photo' => env('ENDPOINT') . 'storage/' . $this->photo,
            'data_ortu' => $this->whenLoaded('parent'),
            'jurusan' => $this->whenLoaded('jurusan'),
            'data_alamat' => $this->whenLoaded('adress'),
            'asal_sekolah' => $this->whenLoaded('fromSchool'),
            'status' => $this->status,
            // 'created_at' => $this->created_at,
            // Add any additional fields or relationships you want to include
        ];
    }
}
