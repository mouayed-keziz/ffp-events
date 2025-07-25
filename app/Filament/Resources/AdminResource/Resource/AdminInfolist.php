<?php

namespace App\Filament\Resources\AdminResource\Resource;

use App\Enums\Role;
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

                    Infolists\Components\TextEntry::make('roles.formatted_name')
                        ->label(__('panel/admins.columns.roles'))
                        ->badge()
                        ->state(fn($record) => $record->roles->first()?->formatted_name)
                        ->color(fn($record) => $record->roles->first()?->color ?? 'gray')
                        ->icon(fn($record) => $record->roles->first()?->icon)
                        ->default(__('panel/admins.empty_states.roles')),

                    Infolists\Components\TextEntry::make('assignedEvents.title')
                        ->label(__('panel/admins.form.assigned_events'))
                        ->badge()
                        ->state(fn($record) => $record->assignedEvents->pluck('title')->join(', '))
                        ->default(__('panel/admins.empty_states.assigned_events'))
                        ->visible(function ($record) {
                            // Only show for hostess role
                            return $record->hasRole(Role::HOSTESS->value);
                        }),

                    Infolists\Components\TextEntry::make('created_at')
                        ->label(__('panel/admins.columns.created_at'))
                        ->dateTime(),
                ])
            ]);
    }
}
