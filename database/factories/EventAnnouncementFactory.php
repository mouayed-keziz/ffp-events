<?php

namespace Database\Factories;

use App\Models\EventAnnouncement;
use App\Enums\Currency;
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
        $startDate    = $this->faker->dateTimeBetween('now', '+2 years');
        $endDate      = $this->faker->dateTimeBetween($startDate, '+3 years');

        $visitorRegStart = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $visitorRegEnd   = $this->faker->dateTimeBetween($visitorRegStart, '+1 month');

        $exhibitorRegStart = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $exhibitorRegEnd   = $this->faker->dateTimeBetween($exhibitorRegStart, '+1 month');


        return [
            'title'       => [
                'en' => $this->faker->realText(30),
                'fr' => $this->faker->realText(30),
                'ar' => $this->faker->realText(30),
            ],
            'description' => [
                'en' => $this->faker->realText(200),
                'fr' => $this->faker->realText(200),
                'ar' => $this->faker->realText(200),
            ],
            'terms'       => [
                'en' => '<p>' . $this->faker->realText(100) . '</p>',
                'fr' => '<p>' . $this->faker->realText(100) . '</p>',
                'ar' => '<p>' . $this->faker->realText(100) . '</p>',
            ],
            'content'     => [
                'en' => '<p>' . $this->faker->realText(200) . '</p>',
                'fr' => '<p>' . $this->faker->realText(200) . '</p>',
                'ar' => '<p>' . $this->faker->realText(200) . '</p>',
            ],
            'location'    => $this->faker->city(),
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'visitor_registration_start_date' => $visitorRegStart,
            'visitor_registration_end_date'   => $visitorRegEnd,
            'exhibitor_registration_start_date' => $exhibitorRegStart,
            'exhibitor_registration_end_date'   => $exhibitorRegEnd,
            'website_url' => $this->faker->url(),
            'contact'     => [
                'name'         => $this->faker->name(),
                'email'        => $this->faker->safeEmail(),
                'phone_number' => $this->faker->phoneNumber(),
            ],
            'currencies'  => array_map(
                fn($currency) => $currency->value,
                $this->faker->randomElements(Currency::cases(), rand(1, count(Currency::cases())))
            ),
        ];
    }
}
