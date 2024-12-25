<?php

namespace Database\Factories;

use Filament\Support\Enums\MaxWidth;
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
        $title = [
            'en' => $this->faker->sentence(),
            'fr' => $this->faker->sentence(),
            'ar' => $this->faker->sentence(),
        ];

        return [
            'title' => $title,
            'slug' => [
                'en' => Str::slug($title['en']),
                'fr' => Str::slug($title['fr']),
                'ar' => Str::slug($title['ar']),
            ],
            'description' => [
                'en' => $this->faker->paragraph(),
                'fr' => $this->faker->paragraph(),
                'ar' => $this->faker->paragraph(),
            ],
            'content' => [
                'en' => $this->faker->realText($this->faker->numberBetween(500, 2000)),
                'fr' => $this->faker->realText($this->faker->numberBetween(500, 2000)),
                'ar' => $this->faker->realText($this->faker->numberBetween(500, 2000)),
            ],
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
