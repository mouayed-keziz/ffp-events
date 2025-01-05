<?php

namespace Database\Seeders;

use App\Models\EventAnnouncement;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventAnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        // Create events for each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create(null, $month, 1);

            // Regular published events (increasing trend)
            EventAnnouncement::factory()
                ->count(ceil($month / 2)) // Halved the count but maintain increasing trend
                ->published()
                ->state([
                    'created_at' => $date,
                    'updated_at' => $date,
                    'start_date' => $date->copy()->addDays(rand(1, 14)),
                    'end_date' => $date->copy()->addDays(rand(15, 28)),
                ])
                ->create();

            // Featured events (random distribution)
            if ($month % 2 == 0) { // Every other month
                EventAnnouncement::factory()
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
            }

            // Draft events (constant low number)
            EventAnnouncement::factory()
                ->count(1) // Reduced from 2 to 1
                ->draft()
                ->state([
                    'created_at' => $date,
                    'updated_at' => $date,
                ])
                ->create();

            // Archived events (decreasing trend)
            EventAnnouncement::factory()
                ->count(max(0, 6 - ceil($month / 2))) // Halved from 12 to 6
                ->archived()
                ->state([
                    'created_at' => $date,
                    'updated_at' => $date,
                    'start_date' => $date->copy()->subDays(rand(1, 14)),
                    'end_date' => $date->copy()->subDays(15),
                ])
                ->create();
        }
    }
}
