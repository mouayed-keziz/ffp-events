<?php

namespace App\Filament\Widgets;

use App\Models\EventAnnouncement;
use Filament\Widgets\ChartWidget;

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

    protected function getType(): string
    {
        return 'bar';
    }
}
