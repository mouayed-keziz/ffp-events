<?php

namespace App\Filament\Resources\ExhibitorResource\Pages;

use App\Filament\Resources\ExhibitorResource;
use App\Notifications\ExhibitorWelcome;
use App\Utils\PasswordUtils;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExhibitor extends CreateRecord
{
    protected static string $resource = ExhibitorResource::class;

    // Store the generated password
    protected string $generatedPassword;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate a random password for the new exhibitor
        $this->generatedPassword = PasswordUtils::generatePassword();

        // Add the generated password to the form data
        $data['password'] = $this->generatedPassword;

        return $data;
    }

    protected function afterCreate(): void
    {
        // Send welcome email with the generated password
        $exhibitor = $this->record;

        // Send welcome notification with the stored password
        $exhibitor->notify(new ExhibitorWelcome($this->generatedPassword));
    }
}
