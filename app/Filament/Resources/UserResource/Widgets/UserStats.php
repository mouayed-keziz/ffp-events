<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\FilamentSimpleStats\SimpleStat;

class UserStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('number of users', '192.1k'),

            SimpleStat::make(User::class)
                ->label('New Users')
                ->last30Days()
                ->dailyCount(),

            SimpleStat::make(User::class)
                ->label('Verified Users')
                ->where('verified_at', '!=', null)
                ->last30Days()
                ->dailyCount(),

            // SimpleStat::make(User::class)
            //     ->label('Active Users')
            //     ->where('verified_at', '!=', null)
            // // ->count(),
        ];
    }
}
