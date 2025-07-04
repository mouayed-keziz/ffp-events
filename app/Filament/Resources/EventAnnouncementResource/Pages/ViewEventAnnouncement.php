<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Resources\EventAnnouncementResource;
use App\Filament\Resources\EventAnnouncementResource\Resource\EventAnnouncementInfolist;
use App\Filament\Resources\EventAnnouncementResource\Widgets\EventExhibitorSubmissionsChart;
use App\Filament\Resources\EventAnnouncementResource\Widgets\EventVisitorSubmissionsChart;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Infolists\Infolist;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class ViewEventAnnouncement extends ViewRecord
{
    use HasPageSidebar;
    use NestedPage;
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = EventAnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            // Actions\EditAction::make()->icon("heroicon-o-pencil")->iconButton()->button()->hiddenLabel(),
            Actions\ForceDeleteAction::make()->icon("heroicon-o-trash")->iconButton()->button()->hiddenLabel(),
            Actions\RestoreAction::make()->icon("heroicon-o-arrow-path")->iconButton()->button()->hiddenLabel(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return EventAnnouncementInfolist::infolist($infolist);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // EventVisitorSubmissionsChart::class,
            // EventExhibitorSubmissionsChart::class,
        ];
    }
}
