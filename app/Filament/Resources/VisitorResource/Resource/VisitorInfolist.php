<?php

namespace App\Filament\Resources\VisitorResource\Resource;

use Filament\Infolists;
use Filament\Infolists\Infolist;

class VisitorInfolist
{
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make([
                    Infolists\Components\TextEntry::make('name')
                        ->label(__('visitors.form.name'))
                        ->default(__('visitors.empty_states.name')),

                    Infolists\Components\TextEntry::make('email')
                        ->label(__('visitors.form.email'))
                        ->default(__('visitors.empty_states.email')),

                    Infolists\Components\TextEntry::make('roles.name')
                        ->label(__('visitors.columns.roles'))
                        ->badge()
                        ->color("gray")
                        ->default(__('visitors.empty_states.roles')),

                    Infolists\Components\TextEntry::make('created_at')
                        ->label(__('visitors.columns.created_at'))
                        ->dateTime(),

                    Infolists\Components\TextEntry::make('verified_at')
                        ->label(__('visitors.columns.verified_at'))
                        ->default(__('visitors.empty_states.verification'))
                    // ->dateTime(),
                ])
            ]);
    }
}
