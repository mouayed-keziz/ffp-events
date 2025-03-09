<?php

namespace App\Filament\Resources\ExhibitorSubmissionResource\Pages;

use App\Filament\Resources\ExhibitorSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class EditExhibitorSubmission extends EditRecord
{
    use NestedPage;

    protected static string $resource = ExhibitorSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            // Actions\ForceDeleteAction::make(),
            // Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index', ['eventAnnouncement' => $this->record->event_announcement_id]);
    }
}
