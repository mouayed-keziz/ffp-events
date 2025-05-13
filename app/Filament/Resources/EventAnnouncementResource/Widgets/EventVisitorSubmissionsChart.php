<?php

namespace App\Filament\Resources\EventAnnouncementResource\Widgets;

use App\Models\EventAnnouncement;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Carbon\Carbon;

class EventVisitorSubmissionsChart extends ChartWidget
{
    public ?EventAnnouncement $record = null;

    protected static ?string $pollingInterval = '30s';
    protected static bool $isLazy = true;
    protected int | string | array $columnSpan = 2;
    protected static ?int $sort = 1;

    public function getHeading(): string | Htmlable | null
    {
        return __('panel/widgets.charts.event_visitor_submissions_heading');
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

        $submissionsByDate = $this->record->visitorSubmissions()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->pluck('count', 'date');

        $data = $dates->merge($submissionsByDate)->values()->map(fn($value) => max(0, $value))->toArray();
        $labels = $dates->keys()->map(fn($date) => Carbon::parse($date)->format('M d'))->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('panel/widgets.charts.visitor_submissions'),
                    'data' => $data,
                    'borderColor' => 'rgb(54, 162, 235)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
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
