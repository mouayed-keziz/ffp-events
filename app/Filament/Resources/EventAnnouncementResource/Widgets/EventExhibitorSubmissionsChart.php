<?php

namespace App\Filament\Resources\EventAnnouncementResource\Widgets;

use App\Models\EventAnnouncement;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Carbon\Carbon;

class EventExhibitorSubmissionsChart extends ChartWidget
{
    public ?EventAnnouncement $record = null;

    protected static ?string $pollingInterval = '30s';
    protected static bool $isLazy = true;
    protected int | string | array $columnSpan = 2;
    protected static ?int $sort = 2;

    public function getHeading(): string | Htmlable | null
    {
        return __('panel/widgets.charts.event_exhibitor_submissions_heading');
    }

    protected function getData(): array
    {
        if (!$this->record) {
            return [];
        }

        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $dates = collect();
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates->put($date->toDateString(), 0);
        }

        $submissionsByDate = $this->record->exhibitorSubmissions()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->pluck('count', 'date');

        $data = $dates->merge($submissionsByDate)->values()->toArray();
        $labels = $dates->keys()->map(fn($date) => Carbon::parse($date)->format('M d'))->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('panel/widgets.charts.exhibitor_submissions'),
                    'data' => $data,
                    'borderColor' => 'rgb(255, 99, 132)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                    'fill' => 'start',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
