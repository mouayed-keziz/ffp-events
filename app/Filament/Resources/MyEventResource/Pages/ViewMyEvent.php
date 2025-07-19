<?php

namespace App\Filament\Resources\MyEventResource\Pages;

use App\Filament\Resources\MyEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMyEvent extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = MyEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }

    public function getTitle(): string
    {
        return $this->getRecord()->title ?? __('panel/my_event.resource.label');
    }
}
