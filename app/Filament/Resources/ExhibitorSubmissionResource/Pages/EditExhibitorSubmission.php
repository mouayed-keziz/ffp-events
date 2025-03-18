<?php

namespace App\Filament\Resources\ExhibitorSubmissionResource\Pages;

use App\Enums\ExhibitorSubmissionStatus;
use App\Enums\SubmissionStatus;
use App\Filament\Resources\ExhibitorSubmissionResource;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class EditExhibitorSubmission extends EditRecord
{
    use NestedPage;

    protected static string $resource = ExhibitorSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            // Actions\ForceDeleteAction::make(),
            // Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("")
                    ->schema([
                        DatePicker::make('edit_deadline')
                            ->label('Edit Deadline')
                            // ->required()
                            ->native(false),
                        Select::make("status")
                            ->options(ExhibitorSubmissionStatus::class)
                    ])
            ]);
    }
}
