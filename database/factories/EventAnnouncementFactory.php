<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EventAnnouncement;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventAnnouncement>
 */
class EventAnnouncementFactory extends Factory
{
    protected $model = EventAnnouncement::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraphs(3, true),
            'start_date' => $this->faker->dateTimeBetween('now', '+2 months'),
            'end_date' => $this->faker->dateTimeBetween('+2 months', '+4 months'),
            'location' => $this->faker->city() . ', ' . $this->faker->country(),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'image_path' => null, // You might want to implement actual image generation if needed
            'max_exhibitors' => $this->faker->numberBetween(10, 100),
            'max_visitors' => $this->faker->numberBetween(100, 1000),
            'is_featured' => $this->faker->boolean(20), // 20% chance of being featured
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }

    /**
     * Indicate that the announcement is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }

    /**
     * Indicate that the announcement is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the announcement is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }
}
