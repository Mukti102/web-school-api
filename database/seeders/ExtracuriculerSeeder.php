<?php

namespace Database\Seeders;

use App\Models\Extracuriculer;
use App\Models\ExtraCuriculerGalery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExtracuriculerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Extracuriculer::factory(2)->create();
        ExtraCuriculerGalery::create([
            'extracuriculer_id' => 1,
            'photo' => 'https://sekolah.flymotion.my.id/wp-content/uploads/2022/04/featured.jpg'
        ]);
    }
}
