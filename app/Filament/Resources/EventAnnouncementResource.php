<?php

namespace App\Filament\Resources;

use App\Filament\Navigation\Sidebar;
use App\Filament\Resources\EventAnnouncementResource\Pages;
use App\Models\EventAnnouncement;
use AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar;
use AymanAlhattami\FilamentPageWithSidebar\PageNavigationItem;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Concerns\Translatable;

class EventAnnouncementResource extends Resource
{

    use Translatable;

    protected static ?string $model = EventAnnouncement::class;
    protected static ?int $navigationSort = Sidebar::EVENT_ANNOUNCEMENT["sort"];
    protected static ?string $navigationIcon = Sidebar::EVENT_ANNOUNCEMENT["icon"];
    protected static bool $shouldRegisterNavigation = true;
    protected static ?string $recordTitleAttribute = 'recordTitle';


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }
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
            // ->setDescription("{$record->description}")
            // ->topbarNavigation()
            ->sidebarNavigation()
            ->setNavigationItems([
                PageNavigationItem::make(__('panel/event_announcement.actions.view'))
                    ->url(fn() => static::getUrl('view', ['record' => $record->id]))
                    ->icon('heroicon-o-eye')
                    ->isActiveWhen(fn() =>
                    request()->routeIs(Pages\ViewEventAnnouncement::getRouteName())),

                PageNavigationItem::make(__('panel/event_announcement.actions.edit'))
                    ->url(fn() => static::getUrl('edit', ['record' => $record->id]))
                    ->icon('heroicon-o-pencil')
                    ->isActiveWhen(
                        fn() =>
                        request()->routeIs(Pages\EditEventAnnouncement::getRouteName())
                    ),
                PageNavigationItem::make(__('panel/event_announcement.actions.edit_terms'))
                    ->url(fn() => static::getUrl('edit-terms', ['record' => $record->id]))
                    ->icon('heroicon-o-document-text')
                    ->isActiveWhen(
                        fn() =>
                        request()->routeIs(Pages\EditEventAnnouncementTerms::getRouteName())
                    ),
                PageNavigationItem::make(__('panel/event_announcement.actions.edit_visitor_form'))
                    ->url(fn() => static::getUrl('edit-visitor-form', ['record' => $record->id]))
                    ->icon('heroicon-o-clipboard-document-check')
                    ->isActiveWhen(
                        fn() =>
                        request()->routeIs(Pages\EditEventAnnouncementVisitorForm::getRouteName())
                    ),
                // PageNavigationItem::make(__('panel/event_announcement.actions.manage_exhibitor_forms'))
                //     ->url(fn() => static::getUrl('manage-exhibitor-forms', ['record' => $record->id]))
                //     ->icon('heroicon-o-clipboard-document-list')
                //     ->isActiveWhen(
                //         fn() =>
                //         request()->routeIs([
                //             Pages\ManageEventAnnouncementExhibitorForms::getRouteName(),
                //         ])
                //     ),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventAnnouncements::route('/'),
            'create' => Pages\CreateEventAnnouncement::route('/create'),
            'view' => Pages\ViewEventAnnouncement::route('/{record}'),
            'edit' => Pages\EditEventAnnouncement::route('/{record}/edit'),
            'edit-terms' => Pages\EditEventAnnouncementTerms::route('/{record}/edit-terms'),
            'edit-visitor-form' => Pages\EditEventAnnouncementVisitorForm::route("/{record}/visitor-form"),
            // 'manage-exhibitor-forms' => Pages\ManageEventAnnouncementExhibitorForms::route('/{record}/exhibitor-forms'),
            // 'create-exhibitor-form' => Pages\CreateEventAnnouncementExhibitorForm::route('/{record}/exhibitor-forms/create'),
            // 'edit-exhibitor-form' => Pages\EditEventAnnouncementExhibitorForm::route('/{record}/exhibitor-forms/{exhibitorForm}'),
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
