<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Resources\EventAnnouncementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventAnnouncement extends EditRecord
{
    protected static string $resource = EventAnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
