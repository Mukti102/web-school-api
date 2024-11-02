<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'NUPTK' => fake()->unique()->text(16),
            'NIP' => fake()->unique()->text(16),
            'start' => fake()->date(),
            'TTL' => fake()->city() . ',' . fake()->date(),
            'religion' => 'islam',
            'gender' => fake()->randomElement(['Laki-Laki', 'Perempuan']),
            'phone' => fake()->phoneNumber(),
            'status' => fake()->randomElement(['PNS', 'Honorer']),
            'email' => fake()->email(),
            'position' => fake()->randomElement(['Guru Mapel', 'Karyawan', 'Guru BK', 'Pustakawan']),
            'address' => fake()->address(),
            'photo' => fake()->imageUrl(640, 480, 'animals'),
        ];
    }
}
