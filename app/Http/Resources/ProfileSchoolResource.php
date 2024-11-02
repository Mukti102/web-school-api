<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileSchoolResource extends JsonResource
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
            'logo' => env('ENDPOINT') . 'storage/' . $this->logo,
            'ttd_lead_of_school' => env('ENDPOINT') . 'storage/' . $this->ttd_lead_of_school,
            'ttd_ketua_panitia' => env('ENDPOINT') . 'storage/' . $this->ttd_ketua_panitia,
            'adress_of_school' => $this->adress_of_school,
            'lead_of_school' => $this->lead_of_school,
            'nip_of_lead_of_school' => $this->nip_of_lead_of_school,
            'phone' => $this->phone,
            'email' => $this->email,
            'ketua_panitia' => $this->ketua_panitia,
            'nip_ketua_panitia' => $this->nip_ketua_panitia,
        ];
    }
}
