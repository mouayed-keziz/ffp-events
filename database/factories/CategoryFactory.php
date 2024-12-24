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
        // Faker instance for English
        $fakerEn = \Faker\Factory::create('en_US');
        // Faker instance for French
        $fakerFr = \Faker\Factory::create('fr_FR');
        // Faker instance for Arabic
        $fakerAr = \Faker\Factory::create('ar_SA');

        $name = [
            'en' => $fakerEn->unique()->realTextBetween(10, 20),  // English name
            'fr' => $fakerFr->unique()->realTextBetween(10, 20),  // French name
            'ar' => $fakerAr->unique()->realTextBetween(10, 20),  // Arabic name
        ];
        // dd($name);
        return [
            'name' => $name,
            'slug' => [
                'en' => Str::slug($name['en']),  // English slug
                'fr' => Str::slug($name['fr']),  // French slug
                'ar' => Str::slug($name['ar']),  // Arabic slug (based on the Arabic name)
            ],
        ];
    }
}
