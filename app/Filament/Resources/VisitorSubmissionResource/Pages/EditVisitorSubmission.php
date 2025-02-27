<?php

namespace App\Filament\Resources\VisitorSubmissionResource\Pages;

use App\Filament\Resources\VisitorSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class EditVisitorSubmission extends EditRecord
{
    use NestedPage;

    protected static string $resource = VisitorSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index', ['eventAnnouncement' => $this->record->event_announcement_id]);
    }
}
