<?php

namespace Database\Factories;

use App\Models\EventAnnouncement;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $startDate = $this->faker->dateTimeBetween('now', '+2 years');
        $endDate = $this->faker->dateTimeBetween($startDate, '+3 years');
        
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'content' => $this->faker->randomHtml(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => $this->faker->city(),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'image_path' => $this->faker->imageUrl(640, 480),
            'max_exhibitors' => $this->faker->numberBetween(10, 100),
            'max_visitors' => $this->faker->numberBetween(100, 1000),
            'is_featured' => $this->faker->boolean(20),
            'publish_at' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
        ];
    }

    /**
     * Indicate that the announcement is published.
     */
    public function published(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'published',
                'publish_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            ];
        });
    }

    /**
     * Indicate that the announcement is a draft.
     */
    public function draft(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'draft',
                'publish_at' => null,
            ];
        });
    }

    /**
     * Indicate that the announcement is archived.
     */
    public function archived(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'archived',
                'publish_at' => $this->faker->dateTimeBetween('-6 months', '-1 month'),
            ];
        });
    }

    /**
     * Indicate that the announcement is featured.
     */
    public function featured(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_featured' => true,
            ];
        });
    }
}
