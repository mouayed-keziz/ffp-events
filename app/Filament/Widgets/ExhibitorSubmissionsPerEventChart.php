<?php

namespace App\Filament\Widgets;

use App\Models\EventAnnouncement;
use Filament\Widgets\ChartWidget;
use Filament\Support\RawJs;

class ExhibitorSubmissionsPerEventChart extends ChartWidget
{
    protected int | string | array $columnSpan = 1;
    protected static ?string $pollingInterval = '30s';
    protected static bool $isLazy = true;

    public function getHeading(): string
    {
        return __('panel/widgets.charts.exhibitor_submissions_per_event_heading');
    }

    protected function getData(): array
    {
        $events = EventAnnouncement::withCount('exhibitorSubmissions')->get();

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
