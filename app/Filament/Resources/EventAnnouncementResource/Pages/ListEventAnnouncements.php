<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Resources\EventAnnouncementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventAnnouncements extends ListRecords
{
    protected static string $resource = EventAnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
