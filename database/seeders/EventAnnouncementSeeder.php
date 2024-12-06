<?php

namespace Database\Seeders;

use App\Models\EventAnnouncement;
use Illuminate\Database\Seeder;

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

        // Create 10 published announcements
        EventAnnouncement::factory()
            ->count(10)
            ->published()
            ->create();

        // Create 8 draft announcements
        EventAnnouncement::factory()
            ->count(8)
            ->draft()
            ->create();

        // Create 3 archived announcements
        EventAnnouncement::factory()
            ->count(3)
            ->archived()
            ->create();
    }
}
