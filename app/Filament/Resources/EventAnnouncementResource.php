<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventAnnouncementResource\Pages;
use App\Filament\Resources\EventAnnouncementResource\Pages\EditEventAnnouncement;
use App\Filament\Resources\EventAnnouncementResource\Pages\ViewEventAnnouncement;
use App\Filament\Resources\EventAnnouncementResource\Resource\EventAnnouncementForm;
use App\Filament\Resources\EventAnnouncementResource\Resource\EventAnnouncementInfolist;
use App\Filament\Resources\EventAnnouncementResource\Resource\EventAnnouncementTable;
use App\Models\EventAnnouncement;
use AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar;
use AymanAlhattami\FilamentPageWithSidebar\PageNavigationItem;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Infolist;

class EventAnnouncementResource extends Resource
{
    protected static ?string $model = EventAnnouncement::class;
    protected static ?int $navigationSort = 4;
    // protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $recordTitleAttribute = 'title';
    public static function getNavigationGroup(): ?string
    {
        return __('panel/nav.groups.event_management');
    }
    public static function getModelLabel(): string
    {
        return __('panel/event_announcement.resource.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel/event_announcement.resource.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
        // return EventAnnouncementForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            \Filament\Tables\Columns\TextColumn::make("id")->badge(),
        ]);
        // return EventAnnouncementTable::table($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([]);
        // return EventAnnouncementInfolist::infolist($infolist);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function sidebar(EventAnnouncement $record): FilamentPageSidebar
    {
        return FilamentPageSidebar::make()
            ->setTitle("{$record->title}")
            ->sidebarNavigation()
            ->setNavigationItems([
                PageNavigationItem::make(__('panel/event_announcement.actions.view'))
                    ->url(fn() => static::getUrl('view', ['record' => $record->id]))
                    ->icon('heroicon-o-eye')
                    ->isActiveWhen(fn() =>
                    request()->routeIs(ViewEventAnnouncement::getRouteName())),

                PageNavigationItem::make(__('panel/event_announcement.actions.edit'))
                    ->url(fn() => static::getUrl('edit', ['record' => $record->id]))
                    ->icon('heroicon-o-pencil')
                    ->isActiveWhen(
                        fn() =>
                        request()->routeIs(EditEventAnnouncement::getRouteName())
                    ),
            ]);
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
