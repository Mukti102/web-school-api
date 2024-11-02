<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author' => $this->faker->name,  // Generates a random name for the author
            'title' => $this->faker->sentence,  // Generates a random sentence for the title
            'description' => $this->faker->paragraph,  // Generates a random paragraph for the description
            'tags' => '',
            'thumbnail' => $this->faker->imageUrl,  // Generates a random image URL for the thumbnail
        ];
    }
}
