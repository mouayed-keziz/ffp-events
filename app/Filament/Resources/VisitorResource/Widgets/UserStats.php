<?php

namespace App\Filament\Resources\VisitorResource\Widgets;

use App\Models\User;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;
use Illuminate\Support\Facades\App;
use Illuminate\Support\HtmlString;
use Spatie\FilamentSimpleStats\SimpleStat;

class UserStats extends BaseWidget
{
    protected function getStats(): array
    {
        $isArabic = App::getLocale() === 'ar';
        $descriptionIconPosition = $isArabic ? 'after' : 'before';
        $margin = $isArabic ? 'margin-right: 50px !important' : '';

        $description = new HtmlString('<span style="' . $margin . '">' . __('panel/visitors.stats.last_30_days') . '</span>');

        return [
            // SimpleStat::make(User::class)
            //     ->last30Days()
            //     ->dailyCount()
            //     ->color('success')
            //     ->icon('heroicon-o-users')
            //     // ->iconColor('info')
            //     ->chartColor("success"),

            Stat::make(__('panel/visitors.stats.total_users'), User::count())
                ->icon('heroicon-o-users')
                ->iconColor('info')
                ->chartColor('info'),

            Stat::make(__('panel/visitors.stats.new_users'), User::whereBetween('created_at', [
                now()->subDays(30),
                now()
            ])->count())
                ->description($description)
                ->descriptionIcon('heroicon-o-arrow-trending-up', $descriptionIconPosition)
                ->descriptionColor('success')
                ->icon('heroicon-o-user-plus')
                ->iconPosition("end")
                ->iconColor('success')
                ->chartColor('success')
                ->chart([
                    User::whereBetween('created_at', [now()->subDays(30), now()->subDays(25)])->count(),
                    User::whereBetween('created_at', [now()->subDays(25), now()->subDays(20)])->count(),
                    User::whereBetween('created_at', [now()->subDays(20), now()->subDays(15)])->count(),
                    User::whereBetween('created_at', [now()->subDays(15), now()->subDays(10)])->count(),
                    User::whereBetween('created_at', [now()->subDays(10), now()->subDays(5)])->count(),
                    User::whereBetween('created_at', [now()->subDays(5), now()])->count(),
                ]),

            Stat::make(__('panel/visitors.stats.verified_users'), User::whereNotNull('verified_at')
                ->whereBetween('verified_at', [
                    now()->subDays(30),
                    now()
                ])->count())
                ->description($description)
                ->descriptionIcon('heroicon-o-check-badge', $descriptionIconPosition)
                ->descriptionColor('warning')
                ->icon('heroicon-o-shield-check')
                ->iconColor('warning')
                ->chartColor('warning')
                ->chart([
                    User::whereNotNull('verified_at')->whereBetween('verified_at', [now()->subDays(30), now()->subDays(25)])->count(),
                    User::whereNotNull('verified_at')->whereBetween('verified_at', [now()->subDays(25), now()->subDays(20)])->count(),
                    User::whereNotNull('verified_at')->whereBetween('verified_at', [now()->subDays(20), now()->subDays(15)])->count(),
                    User::whereNotNull('verified_at')->whereBetween('verified_at', [now()->subDays(15), now()->subDays(10)])->count(),
                    User::whereNotNull('verified_at')->whereBetween('verified_at', [now()->subDays(10), now()->subDays(5)])->count(),
                    User::whereNotNull('verified_at')->whereBetween('verified_at', [now()->subDays(5), now()])->count(),
                ]),
        ];
    }
}
