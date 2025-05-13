<?php

namespace App\Filament\Widgets;

use App\Models\EventAnnouncement;
use Filament\Widgets\ChartWidget;
use Filament\Support\RawJs;

class VisitorSubmissionsPerEventChart extends ChartWidget
{
    protected int | string | array $columnSpan = 1;
    protected static ?string $pollingInterval = '30s';
    protected static bool $isLazy = true;

    public function getHeading(): string
    {
        return __('panel/widgets.charts.visitor_submissions_per_event_heading');
    }

    protected function getData(): array
    {
        $events = EventAnnouncement::withCount('visitorSubmissions')->get();

        $labels = $events->pluck('title')->map(function ($title) {
            return strlen($title) > 30 ? substr($title, 0, 27) . '...' : $title;
        })->toArray();
        $data = $events->pluck('visitor_submissions_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('panel/widgets.charts.visitor_submissions'),
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgb(54, 162, 235)',
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
