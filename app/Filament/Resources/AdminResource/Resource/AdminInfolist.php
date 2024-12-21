<?php

namespace App\Filament\Resources\AdminResource\Resource;

use Filament\Infolists;
use Filament\Infolists\Infolist;

class AdminInfolist
{
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make([
                    Infolists\Components\TextEntry::make('name')
                        ->label(__('exhibitors.form.name'))
                        ->default(__('exhibitors.empty_states.name')),

                    Infolists\Components\TextEntry::make('email')
                        ->label(__('exhibitors.form.email'))
                        ->default(__('exhibitors.empty_states.email')),

                    Infolists\Components\TextEntry::make('roles.name')
                        ->label(__('exhibitors.columns.roles'))
                        ->badge()
                        ->color("gray")
                        ->default(__('exhibitors.empty_states.roles')),

                    Infolists\Components\TextEntry::make('created_at')
                        ->label(__('exhibitors.columns.created_at'))
                        ->dateTime(),

                    Infolists\Components\TextEntry::make('verified_at')
                        ->label(__('exhibitors.columns.verified_at'))
                        ->dateTime(),
                ])
            ]);
    }
}
