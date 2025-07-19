<?php

namespace App\Filament\Resources;

use App\Filament\Navigation\Sidebar;
use App\Filament\Resources\MyEventResource\Pages;
use App\Filament\Resources\MyEventResource\RelationManagers;
use App\Models\MyEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Concerns\Translatable;
use Illuminate\Support\Facades\Auth;
use App\Enums\Role;

class MyEventResource extends Resource
{
    use Translatable;

    protected static ?string $model = MyEvent::class;

    protected static ?string $navigationIcon = Sidebar::MY_EVENT['icon'];

    protected static ?int $navigationSort = Sidebar::MY_EVENT['sort'];

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->hasRole(Role::HOSTESS->value) ?? false;
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole(Role::HOSTESS->value) ?? false;
    }

    public static function getNavigationGroup(): ?string
    {
        return __(Sidebar::MY_EVENT['group']);
    }

    public static function getModelLabel(): string
    {
        return __('panel/my_event.resource.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel/my_event.resource.plural_label');
    }

    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getEloquentQuery()->count();
    // }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // This resource is read-only, so no form schema needed
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label(__('panel/my_event.fields.image'))
                    ->circular()
                    ->defaultImageUrl(asset('placeholder.png')),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('panel/my_event.table.columns.title'))
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),

                Tables\Columns\TextColumn::make('location')
                    ->label(__('panel/my_event.table.columns.location'))
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label(__('panel/my_event.table.columns.start_date'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label(__('panel/my_event.table.columns.end_date'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label(__('panel/my_event.table.columns.status'))
                    ->getStateUsing(function ($record) {
                        $now = now();
                        if ($now->lt($record->start_date)) {
                            return 'upcoming';
                        } elseif ($now->between($record->start_date, $record->end_date)) {
                            return 'ongoing';
                        } else {
                            return 'past';
                        }
                    })
                    ->colors([
                        'warning' => 'upcoming',
                        'success' => 'ongoing',
                        'gray' => 'past',
                    ])
                    ->formatStateUsing(function (string $state): string {
                        return __("panel/my_event.status.{$state}");
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('current_attendees_count')
                    ->label('Current Attendees')
                    ->counts('currentAttendees')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('badge_check_logs_count')
                    ->label('Total Logs')
                    ->counts('badgeCheckLogs')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('panel/my_event.table.filters.status'))
                    ->options([
                        'upcoming' => __('panel/my_event.status.upcoming'),
                        'ongoing' => __('panel/my_event.status.ongoing'),
                        'past' => __('panel/my_event.status.past'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!$data['value']) {
                            return $query;
                        }

                        $now = now();
                        return match ($data['value']) {
                            'upcoming' => $query->where('start_date', '>', $now),
                            'ongoing' => $query->where('start_date', '<=', $now)->where('end_date', '>=', $now),
                            'past' => $query->where('end_date', '<', $now),
                            default => $query,
                        };
                    }),

                Tables\Filters\Filter::make('date_range')
                    ->label(__('panel/my_event.table.filters.date_range'))
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('To Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('start_date', '>=', $date),
                            )
                            ->when(
                                $data['end_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('end_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // No bulk actions for read-only resource
            ])
            ->defaultSort('start_date', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('panel/my_event.infolist.sections.basic_info'))
                    ->schema([
                        Infolists\Components\ImageEntry::make('image')
                            ->label(__('panel/my_event.fields.image'))
                            ->hiddenLabel()
                            ->height(200)
                            ->width('100%'),

                        Infolists\Components\TextEntry::make('title')
                            ->label(__('panel/my_event.fields.title'))
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold'),

                        Infolists\Components\TextEntry::make('description')
                            ->label(__('panel/my_event.fields.description'))
                            ->markdown()
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('location')
                            ->label(__('panel/my_event.fields.location'))
                            ->icon('heroicon-o-map-pin'),

                        Infolists\Components\TextEntry::make('website_url')
                            ->label(__('panel/my_event.fields.website_url'))
                            ->url(fn($record) => $record->website_url)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-globe-alt'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make(__('panel/my_event.infolist.sections.dates'))
                    ->schema([
                        Infolists\Components\TextEntry::make('start_date')
                            ->label(__('panel/my_event.fields.start_date'))
                            ->dateTime('F j, Y \a\t g:i A')
                            ->icon('heroicon-o-calendar'),

                        Infolists\Components\TextEntry::make('end_date')
                            ->label(__('panel/my_event.fields.end_date'))
                            ->dateTime('F j, Y \a\t g:i A')
                            ->icon('heroicon-o-calendar'),

                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->getStateUsing(function ($record) {
                                $now = now();
                                if ($now->lt($record->start_date)) {
                                    return 'upcoming';
                                } elseif ($now->between($record->start_date, $record->end_date)) {
                                    return 'ongoing';
                                } else {
                                    return 'past';
                                }
                            })
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'upcoming' => 'warning',
                                'ongoing' => 'success',
                                'past' => 'gray',
                            })
                            ->formatStateUsing(function (string $state): string {
                                return __("panel/my_event.status.{$state}");
                            }),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make(__('panel/my_event.infolist.sections.registration_dates'))
                    ->schema([
                        Infolists\Components\TextEntry::make('visitor_registration_start_date')
                            ->label(__('panel/my_event.fields.visitor_registration_start_date'))
                            ->dateTime('F j, Y \a\t g:i A')
                            ->placeholder('Not set'),

                        Infolists\Components\TextEntry::make('visitor_registration_end_date')
                            ->label(__('panel/my_event.fields.visitor_registration_end_date'))
                            ->dateTime('F j, Y \a\t g:i A')
                            ->placeholder('Not set'),

                        Infolists\Components\TextEntry::make('exhibitor_registration_start_date')
                            ->label(__('panel/my_event.fields.exhibitor_registration_start_date'))
                            ->dateTime('F j, Y \a\t g:i A')
                            ->placeholder('Not set'),

                        Infolists\Components\TextEntry::make('exhibitor_registration_end_date')
                            ->label(__('panel/my_event.fields.exhibitor_registration_end_date'))
                            ->dateTime('F j, Y \a\t g:i A')
                            ->placeholder('Not set'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make(__('panel/my_event.infolist.sections.additional_info'))
                    ->schema([
                        Infolists\Components\TextEntry::make('contact')
                            ->label(__('panel/my_event.fields.contact'))
                            ->getStateUsing(function ($record) {
                                if (is_array($record->contact)) {
                                    return collect($record->contact)->map(function ($contact, $key) {
                                        return "{$key}: {$contact}";
                                    })->join(' , ');
                                }
                                return $record->contact;
                            })
                            ->placeholder('Not set'),

                        // Infolists\Components\TextEntry::make('currencies')
                        //     ->label(__('panel/my_event.fields.currencies'))
                        //     ->getStateUsing(function ($record) {
                        //         if (is_array($record->currencies)) {
                        //             return collect($record->currencies)->join(', ');
                        //         }
                        //         return $record->currencies;
                        //     })
                        //     ->placeholder('Not set'),

                        // Infolists\Components\TextEntry::make('terms')
                        //     ->label(__('panel/my_event.fields.terms'))
                        //     ->markdown()
                        //     ->columnSpanFull()
                        //     ->placeholder('Not set'),

                        // Infolists\Components\TextEntry::make('content')
                        //     ->label(__('panel/my_event.fields.content'))
                        //     ->markdown()
                        //     ->columnSpanFull()
                        //     ->placeholder('Not set'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CurrentAttendeesRelationManager::class,
            RelationManagers\BadgeCheckLogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyEvents::route('/'),
            'view' => Pages\ViewMyEvent::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('hostesses', function (Builder $query) {
                $query->where('users.id', Auth::id());
            })
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
