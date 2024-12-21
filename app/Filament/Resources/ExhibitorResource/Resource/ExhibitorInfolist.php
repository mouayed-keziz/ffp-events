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
                        ->label(__('exhibitor.form.name'))
                        ->default(__('exhibitor.empty_states.name')),

                    Infolists\Components\TextEntry::make('email')
                        ->label(__('exhibitor.form.email'))
                        ->default(__('exhibitor.empty_states.email')),

                    Infolists\Components\TextEntry::make('roles.name')
                        ->label(__('exhibitor.columns.roles'))
                        ->badge()
                        ->color("gray")
                        ->default(__('exhibitor.empty_states.roles')),

                    Infolists\Components\TextEntry::make('created_at')
                        ->label(__('exhibitor.columns.created_at'))
                        ->dateTime(),

                    Infolists\Components\TextEntry::make('verified_at')
                        ->label(__('exhibitor.columns.verified_at'))
                        ->dateTime(),
                ])
            ]);
    }
}
