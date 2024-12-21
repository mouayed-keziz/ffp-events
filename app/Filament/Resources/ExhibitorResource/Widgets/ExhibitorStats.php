<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;
use Illuminate\Support\Facades\App;
use Illuminate\Support\HtmlString;
use Spatie\FilamentSimpleStats\SimpleStat;

class ExhibitorStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            SimpleStat::make(User::class)
                ->last30Days()
                ->dailyCount()
                ->color("success")
                ->label(__('visitors.stats.total_users'))
                ->description(__('visitors.stats.last_30_days')),

            SimpleStat::make(User::class)
                ->last30Days()
                ->dailyCount()
                ->color("danger")
                ->label(__('visitors.stats.total_users'))
                ->description(__('visitors.stats.last_30_days')),

            SimpleStat::make(User::class)
                ->last30Days()
                ->dailyCount()
                ->icon("heroicon-o-users")
                ->color("primary")
                ->label(__('visitors.stats.total_users'))
                ->description(__('visitors.stats.last_30_days')),
        ];
    }
}
