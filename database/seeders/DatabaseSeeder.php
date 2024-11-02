<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Agenda;
use App\Models\Announcment;
use App\Models\Article;
use App\Models\Category_article;
use App\Models\Extracuriculer;
use App\Models\ExtraCuriculerGalery;
use App\Models\Facility;
use App\Models\FacilityGalery;
use App\Models\Galery;
use App\Models\Jurusan;
use App\Models\JurusanGalery;
use App\Models\Prestation;
use App\Models\ProfileSchool;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Video;
use App\Models\VisiMisi;
use Faker\Guesser\Name;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        \App\Models\User::factory(2)->create();
        Teacher::factory(20)->create();
        Agenda::factory(20)->create();
        Announcment::factory(20)->create();
        Prestation::factory(20)->create();
        $this->call(DefaultPageSeeder::class);
        $this->call(ArticleSeeder::class);
        $this->call(ExtracuriculerSeeder::class);
        $this->call(FacilitySeeder::class);
        $this->call(GalerySeeder::class);
        $this->call(JurusanSeeder::class);
        $this->call(PendaftaranPageSeeder::class);
        $this->call(SchollDataSeeder::class);
        $this->call(VideoSeeder::class);
        $this->call(PendaftaranPageSeeder::class);
        $this->call(PendaftaranPageSeeder::class);
        $this->call(StudentSeeder::class);
        $this->call(ProfileSchoolSeeder::class);
    }
}
