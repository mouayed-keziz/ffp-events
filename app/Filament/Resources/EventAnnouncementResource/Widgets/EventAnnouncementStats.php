<?php

namespace App\Filament\Resources\EventAnnouncementResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EventAnnouncementStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Hello', 'World')
        ];
    }
}
