<?php

namespace App\Filament\Resources\EventAnnouncementResource\Resource;

use Filament\Forms;
use Filament\Forms\Form;

class EventAnnouncementForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make(__('panel/event_announcement.resource.label'))
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('panel/event_announcement.tabs.general'))
                            ->schema([

                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->label(__('panel/event_announcement.fields.title'))
                                    ->translatable(),

                                Forms\Components\Textarea::make('description')
                                    ->maxLength(65535)
                                    ->label(__('panel/event_announcement.fields.description'))
                                    ->translatable(),


                                Forms\Components\RichEditor::make('content')
                                    ->required()
                                    ->label(__('panel/event_announcement.fields.content'))
                                    ->translatable(),

                            ])->columns(1),

                        Forms\Components\Tabs\Tab::make(__('panel/event_announcement.tabs.dates_location'))
                            ->schema([

                                Forms\Components\DateTimePicker::make('start_date')
                                    ->required()
                                    ->native(false)
                                    ->label(__('panel/event_announcement.fields.start_date')),

                                Forms\Components\DateTimePicker::make('end_date')
                                    ->required()
                                    ->native(false)
                                    ->label(__('panel/event_announcement.fields.end_date')),

                                Forms\Components\TextInput::make('location')
                                    ->maxLength(255)
                                    ->label(__('panel/event_announcement.fields.location'))
                                    ->placeholder(__('panel/event_announcement.empty_states.location')),

                            ])->columns(3),

                        Forms\Components\Tabs\Tab::make(__('panel/event_announcement.tabs.details'))
                            ->schema([

                                Forms\Components\Select::make('status')
                                    ->options([
                                        'draft' => __('panel/event_announcement.filters.draft'),
                                        'published' => __('panel/event_announcement.filters.published'),
                                        'archived' => __('panel/event_announcement.filters.archived'),
                                    ])
                                    ->required()
                                    ->default('draft')
                                    ->label(__('panel/event_announcement.fields.status')),

                                Forms\Components\Toggle::make('is_featured')
                                    ->default(false)
                                    ->label(__('panel/event_announcement.fields.is_featured')),

                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('max_exhibitors')
                                            ->numeric()
                                            ->minValue(0)
                                            ->label(__('panel/event_announcement.fields.max_exhibitors')),

                                        Forms\Components\TextInput::make('max_visitors')
                                            ->numeric()
                                            ->minValue(0)
                                            ->label(__('panel/event_announcement.fields.max_visitors')),

                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('panel/event_announcement.tabs.media'))
                            ->schema([

                                Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                                    ->image()
                                    ->imageEditor()
                                    ->collection("image")
                                    ->directory('event-announcements')
                                    ->label(__('panel/event_announcement.fields.image'))
                                    ->placeholder(__('panel/event_announcement.empty_states.photo')),

                            ])->columns(1),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
