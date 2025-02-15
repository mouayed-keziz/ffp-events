<?php

namespace Database\Seeders;

use App\Models\EventAnnouncement;
use App\Models\ExhibitorForm;
use Illuminate\Database\Seeder;

class EventAnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        EventAnnouncement::factory(10)->create();
    }
}
