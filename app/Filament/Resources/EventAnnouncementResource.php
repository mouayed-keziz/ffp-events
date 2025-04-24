<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use App\Filament\Navigation\Sidebar;
use App\Filament\Resources\EventAnnouncementResource\Pages;
use App\Models\EventAnnouncement;
use AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar;
use AymanAlhattami\FilamentPageWithSidebar\PageNavigationItem;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Concerns\Translatable;
use Guava\FilamentNestedResources\Ancestor;
use Guava\FilamentNestedResources\Concerns\NestedResource;

class EventAnnouncementResource extends Resource
{
    use NestedResource;
    use Translatable;

    public static function getAncestor(): ?Ancestor
    {
        return null;
    }

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
        return __(Sidebar::EVENT_ANNOUNCEMENT["group"]);
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
        $GENERAL_GROUP = __('panel/event_announcement.sidebar_groups.general');
        $EVENT_MANAGEMENT = __('panel/event_announcement.sidebar_groups.event_management');
        $FORM_MANAGEMENT = __('panel/event_announcement.sidebar_groups.form_management');
        $REGISTRATION_MANAGEMENT = __('panel/event_announcement.sidebar_groups.registrations');
        return FilamentPageSidebar::make()
            ->setTitle("{$record->title}")
            ->setDescription("{$record->description}")
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
                    ->hidden(fn() => !auth()->user()->hasRole(Role::SUPER_ADMIN->value))
                    ->group($EVENT_MANAGEMENT)
                    ->isActiveWhen(
                        fn() =>
                        request()->routeIs(Pages\EditEventAnnouncement::getRouteName())
                    ),
                PageNavigationItem::make(__('panel/event_announcement.actions.edit_terms'))
                    ->url(fn() => static::getUrl('edit-terms', ['record' => $record->id]))
                    ->icon('heroicon-o-document-text')
                    ->hidden(fn() => !auth()->user()->hasRole(Role::SUPER_ADMIN->value))
                    ->group($EVENT_MANAGEMENT)
                    ->isActiveWhen(
                        fn() =>
                        request()->routeIs(Pages\EditEventAnnouncementTerms::getRouteName())
                    ),
                PageNavigationItem::make(__('panel/event_announcement.actions.edit_visitor_form'))
                    ->url(fn() => static::getUrl('edit-visitor-form', ['record' => $record->id]))
                    ->icon('heroicon-o-clipboard-document-check')
                    ->hidden(fn() => !auth()->user()->hasRole(Role::SUPER_ADMIN->value))
                    ->group($FORM_MANAGEMENT)
                    ->isActiveWhen(
                        fn() =>
                        request()->routeIs(Pages\EditEventAnnouncementVisitorForm::getRouteName())
                    ),
                PageNavigationItem::make(__('panel/event_announcement.actions.manage_exhibitor_forms'))
                    ->url(fn() => static::getUrl('exhibitor-forms', ['record' => $record->id]))
                    ->icon('heroicon-o-clipboard-document-list')
                    ->hidden(fn() => !auth()->user()->hasRole(Role::SUPER_ADMIN->value))
                    ->group($FORM_MANAGEMENT)
                    ->isActiveWhen(fn() => request()->routeIs([Pages\ManageEventAnnouncementExhibitorForms::getRouteName()])),

                PageNavigationItem::make(__('panel/event_announcement.actions.manage_exhibitor_post_payment_forms'))
                    ->url(fn() => static::getUrl('exhibitor-post-payment-forms', ['record' => $record->id]))
                    ->icon('heroicon-o-clipboard-document-list')
                    ->hidden(fn() => !auth()->user()->hasRole(Role::SUPER_ADMIN->value))
                    ->group($FORM_MANAGEMENT)
                    ->isActiveWhen(fn() => request()->routeIs([Pages\ManageEventAnnouncementExhibitorPostPaymentForms::getRouteName()])),

                PageNavigationItem::make(__('panel/event_announcement.actions.manage_visitor_submissions'))
                    ->url(fn() => static::getUrl('visitor-submissions', ['record' => $record->id]))
                    ->icon('heroicon-o-user-group')
                    ->group($REGISTRATION_MANAGEMENT)
                    ->isActiveWhen(fn() => request()->routeIs([Pages\ManageEventAnnouncementVisitorSubmissions::getRouteName()])),

                PageNavigationItem::make(__('panel/event_announcement.actions.manage_exhibitor_submissions'))
                    ->url(fn() => static::getUrl('exhibitor-submissions', ['record' => $record->id]))
                    ->icon('heroicon-o-user-group')
                    ->group($REGISTRATION_MANAGEMENT)
                    ->isActiveWhen(fn() => request()->routeIs([Pages\ManageEventAnnouncementExhibitorSubmissions::getRouteName()])),
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

            'exhibitor-forms' => Pages\ManageEventAnnouncementExhibitorForms::route('/{record}/exhibitor-forms'),
            'exhibitorForms.create' => Pages\CreateEventAnnouncementExhibitorForm::route('/{record}/exhibitor-forms/create'),

            'exhibitor-post-payment-forms' => Pages\ManageEventAnnouncementExhibitorPostPaymentForms::route('/{record}/exhibitor-post-payment-forms'),
            'exhibitorPostPaymentForms.create' => Pages\CreateEventAnnouncementExhibitorPostPaymentForm::route('/{record}/exhibitor-post-payment-forms/create'),

            // New routes for visitor submissions
            'visitor-submissions' => Pages\ManageEventAnnouncementVisitorSubmissions::route('/{record}/visitor-submissions'),
            'visitorSubmissions.create' => Pages\CreateEventAnnouncementVisitorSubmission::route('/{record}/visitor-submissions/create'),
            'visitor-submission.view' => Pages\ViewVisitorSubmission::route('/{record}/visitor-submissions/{visitorSubmission}'),

            // New routes for visitor submissions
            'exhibitor-submissions' => Pages\ManageEventAnnouncementExhibitorSubmissions::route('/{record}/exhibitor-submissions'),
            // 'exhibitor-submission.view' => Pages\ViewExhibitorSubmission::route('/{record}/exhibitor-submissions/{exhibitorSubmission}'),
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
