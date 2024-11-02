<?php

namespace Database\Seeders;

use App\Models\PendaftaranPage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PendaftaranPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PendaftaranPage::create([
            'title' => 'Pendaftaran',
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. At, reiciendis",
            'background' => 'pendaftaran.png'
        ]);
    }
}
