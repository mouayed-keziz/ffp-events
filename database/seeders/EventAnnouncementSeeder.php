<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventAnnouncement;

class EventAnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 published and featured announcements
        EventAnnouncement::factory()
            ->count(5)
            ->published()
            ->featured()
            ->create();

        // Create 10 published but not featured announcements
        EventAnnouncement::factory()
            ->count(10)
            ->published()
            ->create();

        // Create 8 draft announcements
        EventAnnouncement::factory()
            ->count(8)
            ->draft()
            ->create();

        // Create 2 archived announcements
        EventAnnouncement::factory()
            ->count(2)
            ->state(['status' => 'archived'])
            ->create();
    }
}
