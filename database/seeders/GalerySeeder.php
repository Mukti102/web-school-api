<?php

namespace Database\Seeders;

use App\Models\Galery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GalerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Galery::create([
            'photo' => fake()->imageUrl()
        ]);
        Galery::create([
            'photo' => fake()->imageUrl()
        ]);
        Galery::create([
            'photo' => fake()->imageUrl()
        ]);
        Galery::create([
            'photo' => fake()->imageUrl()
        ]);
        Galery::create([
            'photo' => fake()->imageUrl()
        ]);
    }
}
