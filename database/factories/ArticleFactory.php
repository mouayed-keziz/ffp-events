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
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'content' => $this->faker->randomHtml(),
            'published' => $this->faker->boolean(20), // 20% chance of being published
        ];
    }

    public function published(): static
    {
        return $this->state(fn(array $attributes) => [
            'published' => true,
        ]);
    }

    public function unpublished(): static
    {
        return $this->state(fn(array $attributes) => [
            'published' => false,
        ]);
    }
}
