<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $title = $this->faker->sentence();
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(),
            'content' => $this->faker->randomHtml(),
            'published_at' => $this->faker->boolean(20) ? $this->faker->dateTimeBetween('-1 year') : null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn(array $attributes) => [
            'published_at' => $this->faker->dateTimeBetween('-1 year'),
        ]);
    }

    public function unpublished(): static
    {
        return $this->state(fn(array $attributes) => [
            'published_at' => null,
        ]);
    }
}
