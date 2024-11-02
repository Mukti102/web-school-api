<?php

namespace Database\Seeders;

use App\Models\AboutSchool;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchollDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AboutSchool::create([
            'tentang_sekolah' => fake()->sentence(5),
            'visi' => fake()->sentence(3),
            'misi' => fake()->sentence(2),
            'thumbnail' => fake()->image()
        ]);
    }
}
