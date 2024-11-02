<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\FacilityGalery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat data Facility terlebih dahulu
        Facility::factory(3)->create();

        // Kemudian buat data FacilityGalery
        FacilityGalery::create([
            'facility_id' => 1,
            'photo' => 'https://sekolah.flymotion.my.id/wp-content/uploads/2022/04/featured.jpg'
        ]);
    }
}
