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
                        ->label(__('panel/admins.form.name'))
                        ->default(__('panel/admins.empty_states.name')),

                    Infolists\Components\TextEntry::make('email')
                        ->label(__('panel/admins.form.email'))
                        ->default(__('panel/admins.empty_states.email')),

                    Infolists\Components\TextEntry::make('roles.name')
                        ->label(__('panel/admins.columns.roles'))
                        ->badge()
                        ->formatStateUsing(function ($state, $record) {
                            $role = $record->roles->first()?->name;
                            return $role ? \App\Enums\Role::tryFrom($role)?->getLabel() ?? $role : null;
                        })
                        ->color(function ($record) {
                            $role = $record->roles->first()?->name;
                            return $role ? \App\Enums\Role::tryFrom($role)?->getColor() : "gray";
                        })
                        ->icon(function ($record) {
                            $role = $record->roles->first()?->name;
                            return $role ? \App\Enums\Role::tryFrom($role)?->getIcon() : null;
                        })
                        ->default(__('panel/admins.empty_states.roles')),

                    Infolists\Components\TextEntry::make('created_at')
                        ->label(__('panel/admins.columns.created_at'))
                        ->dateTime(),

                    Infolists\Components\TextEntry::make('verified_at')
                        ->label(__('panel/admins.columns.verified_at'))
                        ->dateTime(),
                ])
            ]);
    }
}
