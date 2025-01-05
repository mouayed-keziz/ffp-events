<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Resources\EventAnnouncementResource;
use App\Filament\Resources\EventAnnouncementResource\Resource\EventAnnouncementForm;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Form;

class CreateEventAnnouncement extends CreateRecord
{
    protected static string $resource = EventAnnouncementResource::class;

    public function form(Form $form): Form
    {
        return EventAnnouncementForm::form($form);
    }
}
