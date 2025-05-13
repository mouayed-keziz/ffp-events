<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\EventAnnouncement;
use App\Models\Exhibitor;
use App\Models\Visitor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GeneralStatsOverview extends BaseWidget
{
    protected static ?int $sort = -3;
    protected int | string | array $columnSpan = 4; // Span both columns of the dashboard

    protected function getStats(): array
    {
        return [
            Stat::make(__('panel/widgets.stats_overview.total_exhibitors'), Exhibitor::count())
                ->icon('heroicon-o-users')
                ->color('success'),
            Stat::make(__('panel/widgets.stats_overview.total_visitors'), Visitor::count())
                ->icon('heroicon-o-users')
                ->color('info'),
            Stat::make(__('panel/widgets.stats_overview.total_events'), EventAnnouncement::count())
                ->icon('heroicon-o-megaphone')
                ->color('primary'),
            Stat::make(__('panel/widgets.stats_overview.total_articles'), Article::count())
                ->icon('heroicon-o-document-duplicate')
                ->color('warning'),
        ];
    }
}
