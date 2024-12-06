<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Resources\EventAnnouncementResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEventAnnouncement extends ViewRecord
{
    protected static string $resource = EventAnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
