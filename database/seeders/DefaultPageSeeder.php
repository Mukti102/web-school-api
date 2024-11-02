<?php

namespace Database\Seeders;

use App\Models\Hero;
use App\Models\PendidikanPage;
use App\Models\SocialMedia;
use App\Models\VisiMisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VisiMisi::factory(1)->create();
        Hero::create([
            'image' => fake()->image()
        ]);
        Hero::create([
            'image' => fake()->image()
        ]);
        PendidikanPage::create([
            'title' => "Title",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea, exercitationem.",
            'potret_photo' => "",
            'lanscape_photo' => "",
            'thumbnail' => "",
        ]);
        SocialMedia::create([
            'instagram' => 'www.instagram.com',
            'facebook' => 'www.facebook.com',
            'youtube' => 'www.youtube.com',
            'twitter' => 'www.twitter.com',
            'linkedind' => 'www.linkedind.com',
        ]);
    }
}
