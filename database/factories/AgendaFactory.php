<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Agenda>
 */
class AgendaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(2),
            'author' => fake()->name(),
            'implementation' => fake()->date(),
            'time' => fake()->time(),
            'location' => fake()->address(),
            'kordinator' => fake()->name(),
            'thumbnail' => fake()->imageUrl(),
        ];
    }
}
