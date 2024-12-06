<?php

namespace App\Filament\Resources\EventAnnouncementResource\Widgets;

use App\Models\EventAnnouncement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EventAnnouncementStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make("hello", "world")
        ];
    }
}
