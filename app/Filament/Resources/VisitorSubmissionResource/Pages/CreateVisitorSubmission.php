<?php

namespace App\Filament\Resources\VisitorSubmissionResource\Pages;

use App\Filament\Resources\VisitorSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class CreateVisitorSubmission extends CreateRecord
{
    use NestedPage;

    protected static string $resource = VisitorSubmissionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index', ['eventAnnouncement' => $this->record->event_announcement_id]);
    }
}
