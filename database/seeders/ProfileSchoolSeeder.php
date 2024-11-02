<?php

namespace Database\Seeders;

use App\Models\ProfileSchool;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        ProfileSchool::create([
            'name' => 'SMK Negeri 1 Pekanbaru',
            'adress_of_school' => 'Jl. Jend. Sudirman No. 1, Pekanbaru',
            'lead_of_school' => 'Sekretaris',
            'nip_of_lead_of_school' => '1990010123456',
            'phone' => '08123456789',
            'email' => 'XGkqI@example.com',
            'ketua_panitia' => 'Ketua Panitia',
            'nip_ketua_panitia' => '1990010123456',
            'ttd_lead_of_school' => 'ttd.png',
            'ttd_ketua_panitia' => 'ttd.png',
            'logo' => 'logo.png',
        ]);
    }
}
