<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Resources\EventAnnouncementResource;
use App\Filament\Resources\EventAnnouncementResource\Resource\EventAnnouncementForm;
use Filament\Resources\Pages\EditRecord;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Forms\Form;

class EditEventAnnouncement extends EditRecord
{
    use HasPageSidebar;

    protected static string $resource = EventAnnouncementResource::class;

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
