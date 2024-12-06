<?php

namespace App\Filament\Resources\EventAnnouncementResource\Widgets;

use App\Models\EventAnnouncement;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Carbon\Carbon;

class EventAnnouncementAdvancedStats extends AdvancedChartWidget
{
    protected static ?string $heading = 'Event Statistics';
    protected static string $color = 'primary';
    protected static ?string $icon = 'heroicon-o-chart-bar';
    protected static ?string $iconColor = 'primary';
    protected static ?string $iconBackgroundColor = 'info';
    protected static ?string $label = 'Monthly events overview';

    protected static ?string $badge = 'Analytics';
    protected static ?string $badgeColor = 'success';
    protected static ?string $badgeIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $badgeIconPosition = 'before';
    protected static ?string $badgeSize = 'sm';

    public ?string $filter = 'year';

    protected function getFilters(): ?array
    {
        return [
            'month' => 'This month',
            'quarter' => 'This quarter',
            'year' => 'This year',
            'all' => 'All time',
        ];
    }

    protected function getData(): array
    {
        $query = EventAnnouncement::query();

        // Apply date filter
        $query = match ($this->filter) {
            'month' => $query->where('created_at', '>=', now()->startOfMonth()),
            'quarter' => $query->where('created_at', '>=', now()->startOfQuarter()),
            'year' => $query->where('created_at', '>=', now()->startOfYear()),
            default => $query
        };

        $months = collect(range(1, 12))->map(function ($month) {
            return Carbon::create(null, $month, 1)->format('M');
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Events',
                    'data' => collect(range(1, 12))->map(function ($month) use ($query) {
                        return $query->whereMonth('created_at', $month)->count();
                    })->toArray(),
                ],
                [
                    'label' => 'Active Events',
                    'data' => collect(range(1, 12))->map(function ($month) use ($query) {
                        return $query->active()->whereMonth('start_date', $month)->count();
                    })->toArray(),
                ],
                [
                    'label' => 'Featured Events',
                    'data' => collect(range(1, 12))->map(function ($month) use ($query) {
                        return $query->where('is_featured', true)->whereMonth('created_at', $month)->count();
                    })->toArray(),
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
