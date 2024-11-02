<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Extracuriculer>
 */
class ExtracuriculerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->colorName(),
            'author' => fake()->name(),
            'description' => fake()->paragraph(2),
            'thumbnail' => fake()->imageUrl(),
        ];
    }
}
