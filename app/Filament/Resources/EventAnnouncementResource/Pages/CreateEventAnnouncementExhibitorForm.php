<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Resources\EventAnnouncementResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Guava\FilamentNestedResources\Concerns\NestedPage;
use Guava\FilamentNestedResources\Pages\CreateRelatedRecord;

class CreateEventAnnouncementExhibitorForm extends CreateRelatedRecord
{
    use NestedPage;

    protected static string $resource = EventAnnouncementResource::class;

    protected static string $relationship = 'exhibitorForms';

    public function getTitle(): string
    {
        return __("panel/forms.exhibitors.add_exhibitor_form");
    }
}
