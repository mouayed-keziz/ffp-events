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
                        ->label(__('panel/visitors.form.name'))
                        ->default(__('panel/visitors.empty_states.name')),

                    Infolists\Components\TextEntry::make('email')
                        ->label(__('panel/visitors.form.email'))
                        ->default(__('panel/visitors.empty_states.email')),

                    Infolists\Components\TextEntry::make('roles.name')
                        ->label(__('panel/visitors.columns.roles'))
                        ->badge()
                        ->color("gray")
                        ->default(__('panel/visitors.empty_states.roles')),

                    Infolists\Components\TextEntry::make('created_at')
                        ->label(__('panel/visitors.columns.created_at'))
                        ->dateTime(),

                    Infolists\Components\TextEntry::make('verified_at')
                        ->label(__('panel/visitors.columns.verified_at'))
                        ->default(__('panel/visitors.empty_states.verification'))
                    // ->dateTime(),
                ])
            ]);
    }
}
