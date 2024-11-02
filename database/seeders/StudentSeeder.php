<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\StudentsAddress;
use App\Models\StudentsParent;
use App\Models\StudentsPreviousSchool;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = Student::create([
            'nomor_registrasi' => fake()->randomNumber(), // Replace with your logic if needed
            'fullname' => fake()->name(),
            'NISN' => fake()->unique()->numerify('NISN#####'), // Unique NISN
            'NIS' => fake()->numerify('NIS#####'), // NIS
            'gender' => fake()->randomElement(['laki-laki', 'perempuan']),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->date(),
            'agama' => fake()->randomElement(['Islam', 'Kristen', 'Hindu', 'Buddha', 'Konghucu']),
            'anak_ke' => fake()->numberBetween(1, 5), // Position among siblings
            'jumlah_saudara' => fake()->numberBetween(0, 5), // Number of siblings
            'no_hp' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'photo' => fake()->imageUrl(), // Random image URL
        ]);

        StudentsParent::create([
            'no_kk' => fake()->unique()->numerify('KK#####'), // Unique Kartu Keluarga number
            'lead_family' => fake()->userName(),
            'student_id' => $student->id,
            'father_name' => fake()->name(),
            'father_nik' => fake()->unique()->numerify('NIK##########'), // Unique NIK
            'father_birth' => fake()->date(), // Father's birth date
            'father_job' => fake()->jobTitle(),
            'father_education' => fake()->randomElement(['SD', 'SMP', 'SMA', 'S1', 'S2', 'S3']),
            'mom_name' => fake()->name(),
            'mom_nik' => fake()->unique()->numerify('NIK##########'), // Unique NIK
            'mom_birth' => fake()->date(), // Mother's birth date
            'mom_job' => fake()->jobTitle(),
            'mom_education' => fake()->randomElement(['SD', 'SMP', 'SMA', 'S1', 'S2', 'S3']),
        ]);

        StudentsAddress::create([
            'student_id' => $student->id,
            'desa' => fake()->streetName(),      // Using street name as a placeholder for 'desa'
            'kecamatan' => fake()->citySuffix(),  // Using city suffix as a placeholder for 'kecamatan'
            'kabupaten' => fake()->city(),        // Generating a fake city for 'kabupaten'
            'provinsi' => fake()->state(),        // Generating a fake state for 'provinsi'
            'kode_pos' => fake()->postcode(),     // Generating a fake postcode for 'kode_pos'
            'address' => fake()->address(),       // Generating a full address
        ]);

        StudentsPreviousSchool::create([
            'student_id' => $student->id,
            'previous_school' => fake()->address(),
            'level' => fake()->randomElement(['SD', 'SMP', 'SMA', 'S1', 'S2', 'S3']),
            'NPSN_school' => fake()->randomNumber()
        ]);
    }
}
