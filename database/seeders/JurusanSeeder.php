<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\JurusanGalery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jurusan::factory(3)->create();
        JurusanGalery::create([
            'jurusan_id' => 1,
            'photo' => 'https://sekolah.flymotion.my.id/wp-content/uploads/2022/04/featured.jpg'
        ]);
        JurusanGalery::create([
            'jurusan_id' => 2,
            'photo' => 'https://sekolah.flymotion.my.id/wp-content/uploads/2022/04/featured.jpg'
        ]);
        JurusanGalery::create([
            'jurusan_id' => 3,
            'photo' => 'https://sekolah.flymotion.my.id/wp-content/uploads/2022/04/featured.jpg'
        ]);
    }
}
