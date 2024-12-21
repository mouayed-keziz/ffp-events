<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventAnnouncementResource\Pages;
use App\Filament\Resources\EventAnnouncementResource\Resource\EventAnnouncementForm;
use App\Filament\Resources\EventAnnouncementResource\Resource\EventAnnouncementInfolist;
use App\Filament\Resources\EventAnnouncementResource\Resource\EventAnnouncementTable;
use App\Models\EventAnnouncement;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Infolist;

class EventAnnouncementResource extends Resource
{
    protected static ?string $model = EventAnnouncement::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationGroup(): ?string
    {
        return __('nav.groups.event_management');
    }

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
        return EventAnnouncementForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return EventAnnouncementTable::table($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return EventAnnouncementInfolist::infolist($infolist);
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
