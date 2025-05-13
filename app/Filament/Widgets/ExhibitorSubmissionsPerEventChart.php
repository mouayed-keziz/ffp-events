<?php

namespace App\Filament\Widgets;

use App\Models\EventAnnouncement;
use Filament\Widgets\ChartWidget;
use Filament\Support\RawJs;
use Carbon\Carbon; // Added import

class ExhibitorSubmissionsPerEventChart extends ChartWidget
{
    protected int | string | array $columnSpan = 1; // Changed from 1
    protected static ?int $sort = 2; // Added sort
    protected static ?string $pollingInterval = '30s';
    protected static bool $isLazy = true;

    public ?string $filter = 'month'; // Added filter property

    public function getHeading(): string
    {
        return __('panel/widgets.charts.exhibitor_submissions_per_event_heading');
    }

    protected function getFilters(): ?array // Added getFilters method
    {
        return [
            'today' => __('panel/widgets.filters.today'),
            'week' => __('panel/widgets.filters.last_7_days'),
            'month' => __('panel/widgets.filters.last_30_days'),
            'year' => __('panel/widgets.filters.last_365_days'),
        ];
    }

    protected function getData(): array
    {
        $now = Carbon::now();
        $startDate = match ($this->filter) {
            'today' => $now->copy()->startOfDay(),
            'week' => $now->copy()->subDays(6)->startOfDay(),
            'month' => $now->copy()->subDays(29)->startOfDay(),
            'year' => $now->copy()->subDays(364)->startOfDay(),
            default => $now->copy()->subDays(29)->startOfDay(),
        };
        $endDate = $now->copy()->endOfDay(); // Submissions up to now

        $events = EventAnnouncement::withCount(['exhibitorSubmissions' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }])->get();

        $labels = $events->pluck('title')->map(function ($title) {
            return strlen($title) > 30 ? substr($title, 0, 27) . '...' : $title;
        })->toArray();
        $data = $events->pluck('exhibitor_submissions_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('panel/widgets.charts.exhibitor_submissions'),
                    'data' => $data,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                    'borderColor' => 'rgb(255, 99, 132)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
        {
            scales: {
                y: {
                    min: 0,
                    ticks: {
                        callback: (value) => {
                            return Number.isInteger(value) ? value : '';
                        },
                    },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true, // Or false if you don't want a legend for a single dataset
                    position: 'top',
                }
            },
            responsive: true,
            maintainAspectRatio: false // Or true depending on desired behavior
        }
    JS);
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
