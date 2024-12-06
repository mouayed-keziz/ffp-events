<?php

namespace App\Filament\Resources\EventAnnouncementResource\Widgets;

use App\Models\EventAnnouncement;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;

class EventAnnouncementStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Events', EventAnnouncement::count())
                ->icon('heroicon-o-calendar')
                ->color('primary')
                // ->description('Total number of events in the system')
                // ->descriptionIcon('heroicon-o-information-circle')
                ->chart([
                    EventAnnouncement::where('created_at', '>=', now()->subDays(6))->count(),
                    EventAnnouncement::where('created_at', '>=', now()->subDays(5))->count(),
                    EventAnnouncement::where('created_at', '>=', now()->subDays(4))->count(),
                    EventAnnouncement::where('created_at', '>=', now()->subDays(3))->count(),
                    EventAnnouncement::where('created_at', '>=', now()->subDays(2))->count(),
                    EventAnnouncement::where('created_at', '>=', now()->subDays(1))->count(),
                    EventAnnouncement::where('created_at', '>=', now())->count(),
                ])
                ->chartColor('primary'),

            Stat::make('Active Events', EventAnnouncement::active()->count())
                ->icon('heroicon-o-play')
                ->color('success')
                ->chart([
                    EventAnnouncement::active()->where('start_date', '>=', now()->subDays(6))->count(),
                    EventAnnouncement::active()->where('start_date', '>=', now()->subDays(5))->count(),
                    EventAnnouncement::active()->where('start_date', '>=', now()->subDays(4))->count(),
                    EventAnnouncement::active()->where('start_date', '>=', now()->subDays(3))->count(),
                    EventAnnouncement::active()->where('start_date', '>=', now()->subDays(2))->count(),
                    EventAnnouncement::active()->where('start_date', '>=', now()->subDays(1))->count(),
                    EventAnnouncement::active()->where('start_date', '>=', now())->count(),
                ])
                ->chartColor('success'),

            Stat::make(
                'Upcoming Events',
                EventAnnouncement::where('start_date', '>', now())->count()
            )
                ->icon('heroicon-o-clock')
                ->color('warning')
                ->description('Events yet to start')
                ->descriptionIcon('heroicon-o-arrow-right'),

            Stat::make(
                'Featured Events',
                EventAnnouncement::where('is_featured', true)->count()
            )
                ->icon('heroicon-o-star')
                ->color('primary')
                ->description('Highlighted events')
                ->descriptionIcon('heroicon-o-sparkles')
                ->progressBarColor('warning')
                ->progress(
                    EventAnnouncement::where('is_featured', true)->count() /
                    max(1, EventAnnouncement::count()) * 100
                )
        ];
    }
}
