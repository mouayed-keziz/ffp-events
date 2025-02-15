<?php

namespace App\Filament\Resources\EventAnnouncementResource\Resource;

use App\Enums\Currency;
use Filament\Forms;
use Filament\Forms\Form;

class EventAnnouncementForm
{
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make(__('panel/event_announcement.resource.label'))
                ->columnSpanFull()
                ->tabs([
                    // General Information (removed location and website_url)
                    Forms\Components\Tabs\Tab::make(__('panel/event_announcement.tabs.general'))
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->label(__('panel/event_announcement.fields.title'))
                                ->translatable(),
                            Forms\Components\Textarea::make('description')
                                ->label(__('panel/event_announcement.fields.description'))
                                ->translatable(),
                            Forms\Components\RichEditor::make('content')
                                ->required()
                                ->label(__('panel/event_announcement.fields.content'))
                                ->translatable(),
                        ]),
                    // Dates & Location
                    Forms\Components\Tabs\Tab::make(__('panel/event_announcement.tabs.dates_location'))
                        ->schema([
                            Forms\Components\Section::make(__('panel/event_announcement.fields.event_dates'))
                                ->collapsible()
                                ->schema([
                                    Forms\Components\DateTimePicker::make('start_date')
                                        ->required()
                                        ->label(__('panel/event_announcement.fields.start_date')),
                                    Forms\Components\DateTimePicker::make('end_date')
                                        ->required()
                                        ->label(__('panel/event_announcement.fields.end_date')),
                                ]),
                            // New section for Location and Website URL
                            Forms\Components\Section::make(__('panel/event_announcement.fields.location'))
                                ->collapsible()
                                ->schema([
                                    Forms\Components\TextInput::make('location')
                                        ->maxLength(255)
                                        ->label(__('panel/event_announcement.fields.location')),
                                    Forms\Components\TextInput::make('website_url')
                                        ->label(__('panel/event_announcement.fields.website_url'))
                                        ->placeholder('https://'),
                                ]),
                            Forms\Components\Section::make(__('panel/event_announcement.fields.visitor_registration'))
                                ->collapsible()
                                ->schema([
                                    Forms\Components\DateTimePicker::make('visitor_registration_start_date')
                                        ->required()
                                        ->label(__('panel/event_announcement.fields.visitor_registration_start_date')),
                                    Forms\Components\DateTimePicker::make('visitor_registration_end_date')
                                        ->required()
                                        ->label(__('panel/event_announcement.fields.visitor_registration_end_date')),
                                ]),
                            Forms\Components\Section::make(__('panel/event_announcement.fields.exhibitor_registration'))
                                ->collapsible()
                                ->schema([
                                    Forms\Components\DateTimePicker::make('exhibitor_registration_start_date')
                                        ->required()
                                        ->label(__('panel/event_announcement.fields.exhibitor_registration_start_date')),
                                    Forms\Components\DateTimePicker::make('exhibitor_registration_end_date')
                                        ->required()
                                        ->label(__('panel/event_announcement.fields.exhibitor_registration_end_date')),
                                ]),
                        ]),
                    // Contact & Currencies
                    Forms\Components\Tabs\Tab::make(__('panel/event_announcement.tabs.contact_currencies'))
                        ->schema([
                            Forms\Components\Fieldset::make('Contact Information')
                                ->schema([
                                    Forms\Components\TextInput::make('contact.name')
                                        ->required()
                                        ->label(__('panel/event_announcement.fields.contact_name'))
                                        ->columnSpanFull(),
                                    Forms\Components\TextInput::make('contact.email')
                                        ->required()
                                        ->email()
                                        ->label(__('panel/event_announcement.fields.contact_email')),
                                    Forms\Components\TextInput::make('contact.phone_number')
                                        ->required()
                                        ->label(__('panel/event_announcement.fields.contact_phone_number')),
                                ]),
                            Forms\Components\Select::make('currencies')
                                ->multiple()
                                ->options(Currency::class)
                                ->required()
                                ->label(__('panel/event_announcement.fields.currencies')),
                        ]),
                    // Media
                    Forms\Components\Tabs\Tab::make(__('panel/event_announcement.tabs.media'))
                        ->schema([
                            Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                                ->image()
                                ->imageEditor()
                                ->collection('image')
                                ->directory('event-announcements')
                                ->label(__('panel/event_announcement.fields.image')),
                        ]),
                ]),
        ]);
    }
}
