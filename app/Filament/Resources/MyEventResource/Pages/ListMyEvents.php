<?php

namespace App\Filament\Resources\MyEventResource\Pages;

use App\Filament\Resources\MyEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMyEvents extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = MyEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }

    public function getTitle(): string
    {
        return __('panel/my_event.resource.plural_label');
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No events assigned';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'You are not currently assigned to any events as a hostess. Contact your administrator to get assigned to events.';
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-calendar-days';
    }
}
