<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Resources\EventAnnouncementResource;
use App\Filament\Resources\EventAnnouncementResource\Resource\EventAnnouncementForm;
use Filament\Resources\Pages\EditRecord;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Forms\Form;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class EditEventAnnouncement extends EditRecord
{
    use HasPageSidebar;
    use NestedPage;

    protected static string $resource = EventAnnouncementResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            static::getResource()::getUrl() => __('panel/breadcrumbs.events'),
            static::getResource()::getUrl("edit", ["record" => $this->getRecord()]) => $this->getRecord()->name ?? $this->getRecord()->title,
            __('panel/breadcrumbs.update_announcement'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->icon("heroicon-o-eye"),
            Actions\DeleteAction::make()->icon("heroicon-o-trash"),
            Actions\ForceDeleteAction::make()->icon("heroicon-o-trash"),
            Actions\RestoreAction::make()->icon("heroicon-o-arrow-path"),
        ];
    }

    public function form(Form $form): Form
    {
        return EventAnnouncementForm::form($form);
    }
}
