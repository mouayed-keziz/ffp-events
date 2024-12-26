<?php

namespace App\Filament\Resources\ExhibitorResource\Resource;

use Filament\Infolists;
use Filament\Infolists\Infolist;

class ExhibitorInfolist
{
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make([
                    Infolists\Components\TextEntry::make('name')
                        ->label(__('panel/exhibitors.form.name'))
                        ->default(__('panel/exhibitors.empty_states.name')),

                    Infolists\Components\TextEntry::make('email')
                        ->label(__('panel/exhibitors.form.email'))
                        ->default(__('panel/exhibitors.empty_states.email')),

                    Infolists\Components\TextEntry::make('roles.name')
                        ->label(__('panel/exhibitors.columns.roles'))
                        ->badge()
                        ->color("gray")
                        ->default(__('panel/exhibitors.empty_states.roles')),

                    Infolists\Components\TextEntry::make('created_at')
                        ->label(__('panel/exhibitors.columns.created_at'))
                        ->dateTime(),

                    Infolists\Components\TextEntry::make('verified_at')
                        ->label(__('panel/exhibitors.columns.verified_at'))
                        ->dateTime(),
                ])
            ]);
    }
}
