<?php

namespace App\Filament\Resources\UserResource\Resource;

use Filament\Infolists;
use Filament\Infolists\Infolist;

class UserInfolist
{
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make([
                    Infolists\Components\TextEntry::make('name')
                        ->label(__('users.form.name'))
                        ->default(__('users.empty_states.name')),

                    Infolists\Components\TextEntry::make('email')
                        ->label(__('users.form.email'))
                        ->default(__('users.empty_states.email')),

                    Infolists\Components\TextEntry::make('roles.name')
                        ->label(__('users.columns.roles'))
                        ->badge()
                        ->color("gray")
                        ->default(__('users.empty_states.roles')),

                    Infolists\Components\TextEntry::make('created_at')
                        ->label(__('users.columns.created_at'))
                        ->dateTime(),

                    Infolists\Components\TextEntry::make('verified_at')
                        ->label(__('users.columns.verified_at'))
                        ->default(__('users.empty_states.verification'))
                    // ->dateTime(),
                ])
            ]);
    }
}
