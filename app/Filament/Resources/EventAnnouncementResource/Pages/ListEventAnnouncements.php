<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Resources\EventAnnouncementResource;
use App\Filament\Resources\EventAnnouncementResource\Resource\EventAnnouncementTable;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Guava\FilamentNestedResources\Concerns\NestedPage;


class ListEventAnnouncements extends ListRecords
{
    use NestedPage;
    use ListRecords\Concerns\Translatable;

    protected static string $resource = EventAnnouncementResource::class;

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make()->icon("heroicon-o-plus"),
        ];
    }
    public function table(Table $table): Table
    {
        return EventAnnouncementTable::table($table);
    }
}
