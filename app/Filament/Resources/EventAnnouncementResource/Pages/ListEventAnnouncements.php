<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Resources\EventAnnouncementResource;
use App\Filament\Resources\EventAnnouncementResource\Resource\EventAnnouncementTable;
use App\Filament\Resources\EventAnnouncementResource\Widgets\EventAnnouncementAdvancedStats;
use App\Filament\Resources\EventAnnouncementResource\Widgets\EventAnnouncementStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;

class ListEventAnnouncements extends ListRecords
{
    protected static string $resource = EventAnnouncementResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            // EventAnnouncementStats::class,
            // EventAnnouncementAdvancedStats::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon("heroicon-o-plus"),
        ];
    }
    public function table(Table $table): Table
    {
        return EventAnnouncementTable::table($table);
    }
}
