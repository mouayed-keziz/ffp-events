<?php

namespace App\Filament\Widgets;

use App\Models\EventAnnouncement;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Support\RawJs;

class VisitorSubmissionsByEventOverTimeChart extends ChartWidget
{
    protected static ?string $pollingInterval = '30s';
    protected static bool $isLazy = true;
    protected int | string | array $columnSpan = 2; // Adjusted columnSpan
    protected static ?int $sort = 3;

    public ?string $filter = 'month'; // Added filter property

    public function getHeading(): string | Htmlable | null
    {
        return __('panel/widgets.charts.visitor_submissions_by_event_over_time_heading');
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
        $endDate = $now->copy()->endOfDay();
        $startDate = match ($this->filter) {
            'today' => $now->copy()->startOfDay(),
            'week' => $now->copy()->subDays(6)->startOfDay(),
            'month' => $now->copy()->subDays(29)->startOfDay(),
            'year' => $now->copy()->subDays(364)->startOfDay(),
            default => $now->copy()->subDays(29)->startOfDay(),
        };

        $dateLabels = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateLabels[] = $date->format('M d');
        }

        $events = EventAnnouncement::with(['visitorSubmissions' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->select('event_announcement_id', DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                ->groupBy('event_announcement_id', 'date')
                ->orderBy('date', 'ASC');
        }])->get();

        $datasets = [];
        $colorPalette = [
            'rgb(54, 162, 235)',
            'rgb(255, 99, 132)',
            'rgb(75, 192, 192)',
            'rgb(255, 205, 86)',
            'rgb(153, 102, 255)',
            'rgb(255, 159, 64)',
            'rgb(201, 203, 207)',
            'rgb(0, 128, 128)',
            'rgb(128, 0, 128)',
            'rgb(255, 0, 0)'
        ];
        $colorIndex = 0;

        foreach ($events as $event) {
            $dailyCounts = [];
            // Initialize daily counts for all dates in the range
            for ($dateLoop = $startDate->copy(); $dateLoop->lte($endDate); $dateLoop->addDay()) {
                $dailyCounts[$dateLoop->toDateString()] = 0;
            }

            // Populate with actual submission counts
            foreach ($event->visitorSubmissions as $submission) {
                $dailyCounts[$submission->date] = (int)$submission->count;
            }

            // Calculate cumulative counts
            $cumulativeData = [];
            $runningTotal = 0;
            foreach ($dailyCounts as $dateStr => $count) {
                $runningTotal += $count;
                $cumulativeData[] = $runningTotal;
            }

            // Only add dataset if there's any submission activity for this event in the period
            if ($runningTotal > 0) {
                $color = $colorPalette[$colorIndex % count($colorPalette)];
                $colorIndex++;

                $datasets[] = [
                    'label' => (strlen($event->title) > 20 ? substr($event->title, 0, 17) . '...' : $event->title) .
                        " ({$runningTotal} " . __('panel/widgets.charts.total') . ")",
                    'data' => $cumulativeData,
                    'borderColor' => $color,
                    'backgroundColor' => str_replace('rgb', 'rgba', str_replace(')', ', 0.1)', $color)),
                    'tension' => 0.2,
                    'fill' => false
                ];
            }
        }

        return [
            'datasets' => $datasets,
            'labels' => $dateLabels,
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
                    position: 'top',
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    JS);
    }

    protected function getType(): string
    {
        return 'line';
    }
}
