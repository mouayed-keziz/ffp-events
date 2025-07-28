<?php

namespace App\Filament\Resources\EventAnnouncementResource\Resource;

use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;

class EventAnnouncementInfolist
{
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make(__('panel/event_announcement.resource.label'))
                    ->persistTabInQueryString()
                    ->columnSpan(2)
                    ->tabs([
                        // General Tab
                        Tabs\Tab::make(__('panel/event_announcement.tabs.general'))
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
                            ]),
                        // Dates Tab
                        Tabs\Tab::make(__('panel/event_announcement.tabs.dates_location'))
                            ->schema([
                                Section::make(__('panel/event_announcement.fields.event_dates'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('start_date')
                                            ->label(__('panel/event_announcement.fields.start_date'))
                                            ->dateTime(),
                                        Infolists\Components\TextEntry::make('end_date')
                                            ->label(__('panel/event_announcement.fields.end_date'))
                                            ->dateTime(),
                                    ])->columns(2),
                                Section::make(__('panel/event_announcement.fields.location'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('location')
                                            ->label(__('panel/event_announcement.fields.location')),
                                        Infolists\Components\TextEntry::make('website_url')
                                            ->label(__('panel/event_announcement.fields.website_url')),
                                    ])->columns(2),
                                Section::make(__('panel/event_announcement.fields.visitor_registration'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('visitor_registration_start_date')
                                            ->label(__('panel/event_announcement.fields.visitor_registration_start_date')),
                                        Infolists\Components\TextEntry::make('visitor_registration_end_date')
                                            ->label(__('panel/event_announcement.fields.visitor_registration_end_date')),
                                    ])->columns(2),
                                Section::make(__('panel/event_announcement.fields.exhibitor_registration'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('exhibitor_registration_start_date')
                                            ->label(__('panel/event_announcement.fields.exhibitor_registration_start_date')),
                                        Infolists\Components\TextEntry::make('exhibitor_registration_end_date')
                                            ->label(__('panel/event_announcement.fields.exhibitor_registration_end_date')),
                                    ])->columns(2),
                            ]),
                        // Contact & Currencies Tab
                        Tabs\Tab::make(__('panel/event_announcement.tabs.contact_currencies'))
                            ->schema([
                                Section::make(__('Contact Information'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('contact.name')
                                            ->label(__('panel/event_announcement.fields.contact_name')),
                                        Infolists\Components\TextEntry::make('contact.email')
                                            ->label(__('panel/event_announcement.fields.contact_email')),
                                        Infolists\Components\TextEntry::make('contact.phone_number')
                                            ->label(__('panel/event_announcement.fields.contact_phone_number')),
                                    ]),
                                // Section::make(__('Currencies'))
                                //     ->schema([
                                //         Infolists\Components\TextEntry::make('currencies')
                                //             ->label(__('panel/event_announcement.fields.currencies')),
                                //     ]),
                            ]),
                        // Media Tab
                        Tabs\Tab::make(__('panel/event_announcement.tabs.media'))
                            ->schema([
                                Infolists\Components\ImageEntry::make('image')
                                    ->label(__('panel/event_announcement.fields.image'))
                                    ->circular()
                                    ->placeholder(__('panel/event_announcement.empty_states.photo')),
                            ]),
                        // Extra Links Tab
                        Tabs\Tab::make(__('panel/event_announcement.tabs.extra_links'))
                            ->schema([
                                Infolists\Components\RepeatableEntry::make('extra_links')
                                    ->label(__('panel/event_announcement.fields.extra_links'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('label')
                                            ->label(__('panel/event_announcement.fields.link_label')),
                                        Infolists\Components\TextEntry::make('url')
                                            ->label(__('panel/event_announcement.fields.link_url'))
                                            ->formatStateUsing(fn($state) => $state ? $state : 'No URL')
                                            ->url(fn($state) => $state),
                                        Infolists\Components\IconEntry::make('active')
                                            ->label(__('panel/event_announcement.fields.link_active'))
                                            ->boolean(),
                                    ])
                                    ->columns(1)
                                    ->placeholder(__('panel/event_announcement.empty_states.extra_links')),
                            ]),
                    ]),
            ]);
    }
}
