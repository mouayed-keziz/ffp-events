<?php

namespace App\Filament\Resources\EventAnnouncementResource\Resource;

use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;

class EventAnnouncementInfolist
{
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('Event Announcement')
                    ->columnSpan(2)
                    ->tabs([
                        Tabs\Tab::make(__('panel/event_announcement.tabs.general'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('title')
                                            ->label(__('panel/event_announcement.fields.title')),

                                        Infolists\Components\TextEntry::make('description')
                                            ->label(__('panel/event_announcement.fields.description'))
                                            ->markdown()
                                            ->placeholder(__('panel/event_announcement.empty_states.description')),

                                        Infolists\Components\TextEntry::make('content')
                                            ->label(__('panel/event_announcement.fields.content'))
                                            ->html()
                                            ->placeholder(__('panel/event_announcement.empty_states.content')),
                                    ])->columns(1),
                            ]),

                        Tabs\Tab::make(__('panel/event_announcement.tabs.dates_location'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('start_date')
                                            ->label(__('panel/event_announcement.fields.start_date'))
                                            ->dateTime(),

                                        Infolists\Components\TextEntry::make('end_date')
                                            ->label(__('panel/event_announcement.fields.end_date'))
                                            ->dateTime(),

                                        Infolists\Components\TextEntry::make('location')
                                            ->label(__('panel/event_announcement.fields.location'))
                                            ->placeholder(__('panel/event_announcement.empty_states.location')),
                                    ])->columns(3),
                            ]),

                        Tabs\Tab::make(__('panel/event_announcement.tabs.details'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Infolists\Components\TextEntry::make('status')
                                                    ->label(__('panel/event_announcement.fields.status')),

                                                Infolists\Components\IconEntry::make('is_featured')
                                                    ->label(__('panel/event_announcement.fields.is_featured'))
                                                    ->icon(fn(bool $state): string => $state ? 'heroicon-s-check' : 'heroicon-s-x-mark')
                                                    ->color(fn(bool $state): string => $state ? 'success' : 'danger'),
                                            ]),

                                        Grid::make(3)
                                            ->schema([
                                                Infolists\Components\TextEntry::make('max_exhibitors')
                                                    ->label(__('panel/event_announcement.fields.max_exhibitors'))
                                                    ->placeholder('-'),

                                                Infolists\Components\TextEntry::make('max_visitors')
                                                    ->label(__('panel/event_announcement.fields.max_visitors'))
                                                    ->placeholder('-'),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('panel/event_announcement.tabs.media'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Infolists\Components\ImageEntry::make('image_path')
                                            ->label(__('panel/event_announcement.fields.image_path'))
                                            ->placeholder(__('panel/event_announcement.empty_states.photo'))
                                            ->circular(),
                                    ])->columns(1),
                            ]),
                    ])
            ]);
    }
}
