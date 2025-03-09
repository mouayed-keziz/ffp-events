<?php

namespace App\Filament\Resources\ExhibitorSubmissionResource\Pages;

use App\Filament\Resources\ExhibitorSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class CreateExhibitorSubmission extends CreateRecord
{
    use NestedPage;

    protected static string $resource = ExhibitorSubmissionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index', ['eventAnnouncement' => $this->record->event_announcement_id]);
    }
}
