<?php

namespace App\Filament\Resources\EventAnnouncementResource\Widgets;

use App\Models\EventAnnouncement;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

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

    // Fix the columnSpan property to match parent class requirements
    protected string|int|array $columnSpan = 'full';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'This week',
            'month' => 'This month',
            'quarter' => 'This quarter',
            'year' => 'This year',
            'all' => 'All time',
        ];
    }

    protected function getData(): array
    {
        return Cache::remember('event_stats_' . $this->filter, 300, function () {
            try {
                $query = EventAnnouncement::query();

                // Get date range and labels based on filter
                [$startDate, $endDate, $labels] = $this->getDateRangeAndLabels();

                $query->whereBetween('created_at', [$startDate, $endDate]);

                return [
                    'datasets' => [
                        [
                            'label' => 'Total Events',
                            'data' => $this->getFilteredData($query, 'created_at', $startDate, $endDate),
                            'borderColor' => '#4F46E5',
                            'tension' => 0.3,
                        ],
                        [
                            'label' => 'Active Events',
                            'data' => $this->getFilteredData($query->active(), 'start_date', $startDate, $endDate),
                            'borderColor' => '#10B981',
                            'tension' => 0.3,
                        ],
                        [
                            'label' => 'Featured Events',
                            'data' => $this->getFilteredData($query->where('is_featured', true), 'created_at', $startDate, $endDate),
                            'borderColor' => '#F59E0B',
                            'tension' => 0.3,
                        ],
                        [
                            'label' => 'Completed Events',
                            'data' => $this->getFilteredData($query->where('end_date', '<', now()), 'end_date', $startDate, $endDate),
                            'borderColor' => '#6B7280',
                            'tension' => 0.3,
                        ],
                    ],
                    'labels' => $labels,
                ];
            } catch (\Exception $e) {
                report($e);
                return $this->getEmptyDataset();
            }
        });
    }

    protected function getDateRangeAndLabels(): array
    {
        return match ($this->filter) {
            'today' => [
                now()->startOfDay(),
                now()->endOfDay(),
                collect(range(0, 23))->map(fn($hour) => sprintf('%02d:00', $hour))->toArray()
            ],
            'week' => [
                now()->startOfWeek(),
                now()->endOfWeek(),
                collect(range(0, 6))->map(fn($day) => now()->startOfWeek()->addDays($day)->format('D'))->toArray()
            ],
            'month' => [
                now()->startOfMonth(),
                now()->endOfMonth(),
                collect(range(1, now()->daysInMonth))->map(fn($day) => $day)->toArray()
            ],
            'quarter' => [
                now()->startOfQuarter(),
                now()->endOfQuarter(),
                collect(range(0, 2))->map(fn($month) => now()->startOfQuarter()->addMonths($month)->format('M'))->toArray()
            ],
            'year' => [
                now()->startOfYear(),
                now()->endOfYear(),
                collect(range(1, 12))->map(fn($month) => Carbon::create(null, $month, 1)->format('M'))->toArray()
            ],
            default => [
                now()->subYears(5),
                now(),
                collect(range(0, 5))->map(fn($year) => now()->subYears($year)->format('Y'))->reverse()->toArray()
            ],
        };
    }

    protected function getFilteredData($query, string $dateColumn, $startDate, $endDate): array
    {
        $query = $query->clone();

        return match ($this->filter) {
            'today' => collect(range(0, 23))
                ->map(
                    fn($hour) => $query->whereDate($dateColumn, today())
                        ->whereHour($dateColumn, $hour)
                        ->count()
                )->toArray(),
            'week' => collect(range(0, 6))
                ->map(
                    fn($day) => $query->whereDate($dateColumn, now()->startOfWeek()->addDays($day))->count()
                )->toArray(),
            'month' => collect(range(1, now()->daysInMonth))
                ->map(
                    fn($day) => $query->whereDate($dateColumn, now()->setDay($day))->count()
                )->toArray(),
            'quarter' => collect(range(0, 2))
                ->map(
                    fn($month) => $query->whereMonth($dateColumn, now()->startOfQuarter()->addMonths($month)->month)->count()
                )->toArray(),
            'year' => collect(range(1, 12))
                ->map(
                    fn($month) => $query->whereMonth($dateColumn, $month)->whereYear($dateColumn, now()->year)->count()
                )->toArray(),
            default => collect(range(0, 5))
                ->map(
                    fn($year) => $query->whereYear($dateColumn, now()->subYears($year)->year)->count()
                )->reverse()->toArray(),
        };
    }

    protected function getEmptyDataset(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'No Data Available',
                    'data' => array_fill(0, 12, 0),
                    'borderColor' => '#6B7280',
                ]
            ],
            'labels' => collect(range(1, 12))->map(
                fn($month) =>
                Carbon::create(null, $month, 1)->format('M')
            )->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'elements' => [
                'point' => [
                    'radius' => 4,
                    'hoverRadius' => 6,
                ],
            ],
        ];
    }
}
