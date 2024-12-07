<?php

namespace App\Filament\Resources\EventAnnouncementResource\Widgets;

use App\Models\EventAnnouncement;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;
use Illuminate\Support\Facades\App;
use Illuminate\Support\HtmlString;

class EventAnnouncementStats extends BaseWidget
{
    protected function getStats(): array
    {
        $isArabic = App::getLocale() === 'ar';
        $descriptionIconPosition = $isArabic ? 'after' : 'before';
        $margin = $isArabic ? 'margin-right: 50px !important' : '';

        $description = new HtmlString('<span style="' . $margin . '">' . __('event_announcement.stats.last_7_days') . '</span>');

        return [
            Stat::make(__('event_announcement.stats.total_events'), EventAnnouncement::count())
                ->icon('heroicon-o-calendar')
                ->color('primary')
                // ->description($description)
                // ->descriptionIcon('heroicon-o-chart-bar', $descriptionIconPosition)
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

            Stat::make(__('event_announcement.stats.active_events'), EventAnnouncement::active()->count())
                ->icon('heroicon-o-play')
                ->color('success')
                // ->description($description)
                // ->descriptionIcon('heroicon-o-arrow-trending-up', $descriptionIconPosition)
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
                __('event_announcement.stats.upcoming_events'),
                EventAnnouncement::where('start_date', '>', now())->count()
            )
                ->icon('heroicon-o-clock')
                ->color('warning')
            // ->description(__('event_announcement.stats.events_to_start'))
            // ->descriptionIcon('heroicon-o-arrow-right', $descriptionIconPosition),
            ,
            Stat::make(
                __('event_announcement.stats.featured_events'),
                EventAnnouncement::where('is_featured', true)->count()
            )
                ->icon('heroicon-o-star')
                ->color('primary')
                ->progressBarColor('warning')
                ->progress(
                    EventAnnouncement::where('is_featured', true)->count() /
                    max(1, EventAnnouncement::count()) * 100
                )
                // Apply custom classes to the progress bar wrapper
                ->extraAttributes([
                    'data-progress-classes' => $isArabic ? '' : '-mr-[50px]',
                ])
        ];
    }
}
