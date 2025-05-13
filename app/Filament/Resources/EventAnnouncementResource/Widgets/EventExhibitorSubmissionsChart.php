<?php

namespace App\Filament\Resources\EventAnnouncementResource\Widgets;

use App\Models\EventAnnouncement;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Carbon\Carbon;
use Filament\Support\RawJs;

class EventExhibitorSubmissionsChart extends ChartWidget
{
    public ?EventAnnouncement $record = null;
    public ?string $filter = 'month'; // Added filter property

    protected static ?string $pollingInterval = '30s';
    protected static bool $isLazy = true;
    protected int | string | array $columnSpan = 2;
    protected static ?int $sort = 2;

    public function getHeading(): string | Htmlable | null
    {
        return __('panel/widgets.charts.event_exhibitor_submissions_heading');
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
        if (!$this->record) {
            return [];
        }

        $now = Carbon::now();
        $endDate = $now->copy()->endOfDay();
        $startDate = match ($this->filter) {
            'today' => $now->copy()->startOfDay(),
            'week' => $now->copy()->subDays(6)->startOfDay(),
            'month' => $now->copy()->subDays(29)->startOfDay(),
            'year' => $now->copy()->subDays(364)->startOfDay(),
            default => $now->copy()->subDays(29)->startOfDay(),
        };

        $dates = collect();
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates->put($date->toDateString(), 0);
        }

        $submissionsByDate = $this->record->exhibitorSubmissions()
            ->whereBetween('created_at', [$startDate, $endDate]) // Updated to use dynamic date range
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->pluck('count', 'date');

        $data = $dates->merge($submissionsByDate)->values()->map(fn($value) => max(0, $value))->toArray();
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
                },
            },
        }
    JS);
    }


    protected function getType(): string
    {
        return 'line';
    }
}
