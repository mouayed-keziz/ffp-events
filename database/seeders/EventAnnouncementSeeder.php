<?php

namespace Database\Seeders;

use App\Models\EventAnnouncement;
use App\Models\ExhibitorForm;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class EventAnnouncementSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        // Initialize Faker
        $this->faker = FakerFactory::create();
    }

    public function run(): void
    {
        // Create events for each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create(null, $month, 1);

            // Regular published events (increasing trend)
            $publishedEvents = EventAnnouncement::factory()
                ->count(ceil($month / 2)) // Halved the count but maintain increasing trend
                ->published()
                ->state([
                    'created_at' => $date,
                    'updated_at' => $date,
                    'start_date' => $date->copy()->addDays(rand(1, 14)),
                    'end_date' => $date->copy()->addDays(rand(15, 28)),
                ])
                ->create();

            // Create exhibitor forms for published events (3 to 8 forms per event)
            foreach ($publishedEvents as $event) {
                ExhibitorForm::factory()
                    ->count($this->faker->numberBetween(3, 8)) // 3 to 8 exhibitor forms per event
                    ->state([
                        'event_announcement_id' => $event->id,
                    ])
                    ->create();
            }

            // Featured events (random distribution)
            if ($month % 2 == 0) { // Every other month
                $featuredEvents = EventAnnouncement::factory()
                    ->count(rand(1, 2)) // Reduced from 1-3 to 1-2
                    ->published()
                    ->featured()
                    ->state([
                        'created_at' => $date,
                        'updated_at' => $date,
                        'start_date' => $date->copy()->addDays(rand(1, 14)),
                        'end_date' => $date->copy()->addDays(15),
                    ])
                    ->create();

                // Create exhibitor forms for featured events (3 to 8 forms per event)
                foreach ($featuredEvents as $event) {
                    ExhibitorForm::factory()
                        ->count($this->faker->numberBetween(3, 8)) // 3 to 8 exhibitor forms per event
                        ->state([
                            'event_announcement_id' => $event->id,
                        ])
                        ->create();
                }
            }

            // Draft events (constant low number)
            $draftEvents = EventAnnouncement::factory()
                ->count(1) // Reduced from 2 to 1
                ->draft()
                ->state([
                    'created_at' => $date,
                    'updated_at' => $date,
                ])
                ->create();

            // Create exhibitor forms for draft events (3 to 8 forms per event)
            foreach ($draftEvents as $event) {
                ExhibitorForm::factory()
                    ->count($this->faker->numberBetween(3, 8)) // 3 to 8 exhibitor forms per event
                    ->state([
                        'event_announcement_id' => $event->id,
                    ])
                    ->create();
            }

            // Archived events (decreasing trend)
            $archivedEvents = EventAnnouncement::factory()
                ->count(max(0, 6 - ceil($month / 2))) // Halved from 12 to 6
                ->archived()
                ->state([
                    'created_at' => $date,
                    'updated_at' => $date,
                    'start_date' => $date->copy()->subDays(rand(1, 14)),
                    'end_date' => $date->copy()->subDays(15),
                ])
                ->create();

            // Create exhibitor forms for archived events (3 to 8 forms per event)
            foreach ($archivedEvents as $event) {
                ExhibitorForm::factory()
                    ->count($this->faker->numberBetween(3, 8)) // 3 to 8 exhibitor forms per event
                    ->state([
                        'event_announcement_id' => $event->id,
                    ])
                    ->create();
            }
        }
    }
}
