<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prestation>
 */
class PrestationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status_winner' => fake()->randomElement(['siswa', 'guru']),
            'title' => fake()->sentence(),
            'author' => fake()->name(),
            'description' => fake()->paragraph(2),
            'date' => fake()->date(),
            'juara' => fake()->sentence(),
            'penyelenggara' => fake()->name(),
            'thumbnail' => fake()->imageUrl(),
        ];
    }
}
