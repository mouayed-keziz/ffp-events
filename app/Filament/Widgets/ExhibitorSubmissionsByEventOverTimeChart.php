<?php

namespace App\Filament\Widgets;

use App\Models\EventAnnouncement;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Support\RawJs; // Added import

class ExhibitorSubmissionsByEventOverTimeChart extends ChartWidget
{
    protected static ?string $pollingInterval = '30s';
    protected static bool $isLazy = true;
    protected int | string | array $columnSpan = 1;
    protected static ?int $sort = 3; // Assuming this is after GeneralStats (1) and PerEventCharts (2)

    public function getHeading(): string | Htmlable | null
    {
        return __('panel/widgets.charts.exhibitor_submissions_by_event_over_time_heading');
    }

    protected function getData(): array
    {
        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $dateLabels = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateLabels[] = $date->format('M d');
        }

        $events = EventAnnouncement::with(['exhibitorSubmissions' => function ($query) use ($startDate) {
            $query->where('created_at', '>=', $startDate)
                ->select('event_announcement_id', DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                ->groupBy('event_announcement_id', 'date')
                ->orderBy('date', 'ASC');
        }])->get();

        $datasets = [];
        $colorPalette = [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(255, 159, 64)',
            'rgb(153, 102, 255)',
            'rgb(201, 203, 207)',
            'rgb(128, 0, 0)',
            'rgb(0, 128, 0)',
            'rgb(255, 0, 255)'
        ];
        $colorIndex = 0;

        foreach ($events as $event) {
            $dailyCounts = [];
            // Initialize daily counts for all dates in the range
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $dailyCounts[$date->toDateString()] = 0;
            }

            // Populate with actual submission counts
            foreach ($event->exhibitorSubmissions as $submission) {
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
                $borderColor = $colorPalette[$colorIndex % count($colorPalette)];
                $backgroundColor = str_replace('rgb(', 'rgba(', $borderColor);
                $backgroundColor = str_replace(')', ', 0.1)', $backgroundColor); // Lighter fill

                $datasets[] = [
                    'label' => strlen($event->title) > 30 ? substr($event->title, 0, 27) . '...' : $event->title,
                    'data' => $cumulativeData,
                    'borderColor' => $borderColor,
                    'backgroundColor' => $backgroundColor, // Optional: for area under line
                    'fill' => true, // Changed to true for better visibility of distinct lines as areas
                    'tension' => 0.1,
                ];
                $colorIndex++;
            }
        }

        return [
            'datasets' => $datasets,
            'labels' => $dateLabels,
            // 'options' key removed
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
