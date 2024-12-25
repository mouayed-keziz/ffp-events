<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {


        $name = [
            'en' => $this->faker->words(2, true),
            'fr' => $this->faker->words(2, true),
            'ar' => $this->faker->words(2, true),
        ];

        return [
            'name' => $name,
            'slug' => [
                'en' => Str::slug($name['en']),
                'fr' => Str::slug($name['fr']),
                'ar' => Str::slug($name['ar']),
            ],
        ];
    }
}
