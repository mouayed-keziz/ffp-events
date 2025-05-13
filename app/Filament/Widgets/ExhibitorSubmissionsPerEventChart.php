<?php

namespace App\Filament\Widgets;

use App\Models\EventAnnouncement;
use Filament\Widgets\ChartWidget;

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

    protected function getType(): string
    {
        return 'bar';
    }
}
