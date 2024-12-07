<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventAnnouncementResource\Pages;
use App\Models\EventAnnouncement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;

class EventAnnouncementResource extends Resource
{
    protected static ?string $model = EventAnnouncement::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Event Management';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('event_announcement.resource.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('event_announcement.resource.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make(__('event_announcement.resource.label'))
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('event_announcement.tabs.general'))
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->label(__('event_announcement.fields.title')),

                                Forms\Components\Textarea::make('description')
                                    ->maxLength(65535)
                                    ->label(__('event_announcement.fields.description')),

                                Forms\Components\RichEditor::make('content')
                                    ->required()
                                    ->label(__('event_announcement.fields.content')),
                            ])->columns(1),

                        Forms\Components\Tabs\Tab::make(__('event_announcement.tabs.dates_location'))
                            ->schema([
                                Forms\Components\DateTimePicker::make('start_date')
                                    ->required()
                                    ->label(__('event_announcement.fields.start_date')),

                                Forms\Components\DateTimePicker::make('end_date')
                                    ->required()
                                    ->label(__('event_announcement.fields.end_date')),

                                Forms\Components\TextInput::make('location')
                                    ->maxLength(255)
                                    ->label(__('event_announcement.fields.location'))
                                    ->placeholder(__('event_announcement.empty_states.location')),
                            ])->columns(3),

                        Forms\Components\Tabs\Tab::make(__('event_announcement.tabs.details'))
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'draft' => __('event_announcement.filters.draft'),
                                        'published' => __('event_announcement.filters.published'),
                                        'archived' => __('event_announcement.filters.archived'),
                                    ])
                                    ->required()
                                    ->default('draft')
                                    ->label(__('event_announcement.fields.status')),

                                Forms\Components\Toggle::make('is_featured')
                                    ->default(false)
                                    ->label(__('event_announcement.fields.is_featured')),

                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('max_exhibitors')
                                            ->numeric()
                                            ->minValue(0)
                                            ->label(__('event_announcement.fields.max_exhibitors')),

                                        Forms\Components\TextInput::make('max_visitors')
                                            ->numeric()
                                            ->minValue(0)
                                            ->label(__('event_announcement.fields.max_visitors')),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('event_announcement.tabs.media'))
                            ->schema([
                                Forms\Components\FileUpload::make('image_path')
                                    ->image()
                                    ->directory('event-announcements')
                                    ->label(__('event_announcement.fields.image_path'))
                                    ->placeholder(__('event_announcement.empty_states.photo')),
                            ])->columns(1),
                    ])
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('event_announcement.fields.title'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('description')
                    ->label(__('event_announcement.fields.description'))
                    ->limit(40)
                    ->placeholder(__('event_announcement.empty_states.description'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->limit(50),

                Tables\Columns\TextColumn::make('dates')
                    ->label(__('event_announcement.fields.event_dates'))
                    ->state(function ($record): string {
                        $startDate = \Carbon\Carbon::parse($record->start_date)->format('d/m/y H:i');
                        $endDate = \Carbon\Carbon::parse($record->end_date)->format('d/m/y H:i');

                        return __('event_announcement.fields.start_date') . ' ' . $startDate .
                            '<br>' .
                            __('event_announcement.fields.end_date') . ' ' . $endDate;
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('start_date', 'like', "%{$search}%")
                            ->orWhere('end_date', 'like', "%{$search}%");
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('start_date', $direction);
                    })
                    ->toggleable()
                    ->html()
                    ->alignment(\Filament\Support\Enums\Alignment::Center),

                Tables\Columns\TextColumn::make('location')
                    ->label(__('event_announcement.fields.location'))
                    ->placeholder(__('event_announcement.empty_states.location'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\ImageColumn::make('image_path')
                    ->label(__('event_announcement.fields.image_path'))
                    ->placeholder(__('event_announcement.empty_states.photo'))
                    ->circular()
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('max_exhibitors')
                    ->label(__('event_announcement.fields.max_exhibitors'))
                    ->alignment(\Filament\Support\Enums\Alignment::Center)
                    ->badge()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('max_visitors')
                    ->label(__('event_announcement.fields.max_visitors'))
                    ->alignment(\Filament\Support\Enums\Alignment::Center)
                    ->badge()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label(__('event_announcement.fields.is_featured'))
                    ->boolean()
                    ->alignment(\Filament\Support\Enums\Alignment::Center)
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\SelectColumn::make('status')
                    ->label(__('event_announcement.fields.status'))
                    ->options([
                        'draft' => __('event_announcement.filters.draft'),
                        'published' => __('event_announcement.filters.published'),
                        'archived' => __('event_announcement.filters.archived'),
                    ])
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('event_announcement.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('event_announcement.fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('event_announcement.fields.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->placeholder(__('event_announcement.empty_states.deleted_at'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('event_announcement.filters.status'))
                    ->options([
                        'draft' => __('event_announcement.filters.draft'),
                        'published' => __('event_announcement.filters.published'),
                        'archived' => __('event_announcement.filters.archived'),
                    ]),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label(__('event_announcement.filters.featured')),

                Tables\Filters\TrashedFilter::make()
                    ->label(__('event_announcement.filters.trashed')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('event_announcement.actions.delete')),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->label(__('event_announcement.actions.force_delete')),
                    Tables\Actions\RestoreBulkAction::make()
                        ->label(__('event_announcement.actions.restore')),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('Event Announcement')
                    ->columnSpan(2)
                    ->tabs([
                        Tabs\Tab::make(__('event_announcement.tabs.general'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('title')
                                            ->label(__('event_announcement.fields.title')),

                                        Infolists\Components\TextEntry::make('description')
                                            ->label(__('event_announcement.fields.description'))
                                            ->markdown()
                                            ->placeholder(__('event_announcement.empty_states.description')),

                                        Infolists\Components\TextEntry::make('content')
                                            ->label(__('event_announcement.fields.content'))
                                            ->html()
                                            ->placeholder(__('event_announcement.empty_states.content')),
                                    ])->columns(1),
                            ]),

                        Tabs\Tab::make(__('event_announcement.tabs.dates_location'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('start_date')
                                            ->label(__('event_announcement.fields.start_date'))
                                            ->dateTime(),

                                        Infolists\Components\TextEntry::make('end_date')
                                            ->label(__('event_announcement.fields.end_date'))
                                            ->dateTime(),

                                        Infolists\Components\TextEntry::make('location')
                                            ->label(__('event_announcement.fields.location'))
                                            ->placeholder(__('event_announcement.empty_states.location')),
                                    ])->columns(3),
                            ]),

                        Tabs\Tab::make(__('event_announcement.tabs.details'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Infolists\Components\TextEntry::make('status')
                                                    ->label(__('event_announcement.fields.status')),

                                                Infolists\Components\IconEntry::make('is_featured')
                                                    ->label(__('event_announcement.fields.is_featured'))
                                                    ->icon(fn(bool $state): string => $state ? 'heroicon-s-check' : 'heroicon-s-x-mark')
                                                    ->color(fn(bool $state): string => $state ? 'success' : 'danger'),
                                            ]),

                                        Grid::make(3)
                                            ->schema([
                                                Infolists\Components\TextEntry::make('max_exhibitors')
                                                    ->label(__('event_announcement.fields.max_exhibitors'))
                                                    ->placeholder('-'),

                                                Infolists\Components\TextEntry::make('max_visitors')
                                                    ->label(__('event_announcement.fields.max_visitors'))
                                                    ->placeholder('-'),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('event_announcement.tabs.media'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Infolists\Components\ImageEntry::make('image_path')
                                            ->label(__('event_announcement.fields.image_path'))
                                            ->placeholder(__('event_announcement.empty_states.photo'))
                                            ->circular(),
                                    ])->columns(1),
                            ]),
                    ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventAnnouncements::route('/'),
            'create' => Pages\CreateEventAnnouncement::route('/create'),
            'view' => Pages\ViewEventAnnouncement::route('/{record}'),
            'edit' => Pages\EditEventAnnouncement::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
