<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\video>
 */
class videoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->title(),
            'description' => fake()->paragraph(3),
            'author' => fake()->name(),
            'thumbnail' => 'https://ugm.ac.id/wp-content/uploads/2024/07/WhatsApp-Image-2024-07-31-at-09.58.42_25b62482-825x464.jpg',
            'video_path' => fake()->url(),
        ];
    }
}
