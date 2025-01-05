<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Resources\EventAnnouncementResource;
use App\Models\EventAnnouncement;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditEventAnnouncementVisitorForm extends EditRecord
{
    use HasPageSidebar;
    protected static string $resource = EventAnnouncementResource::class;

    public function form(Form $form): Form
    {
        return  $form
            ->schema([
                \Filament\Forms\Components\Section::make()->schema([
                    \Filament\Forms\Components\Repeater::make('fields')
                        ->label('Visitor Form Fields')
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('label')
                                ->label('Field Label')
                                ->required()
                                ->translatable(),
                            \Filament\Forms\Components\Select::make('type')
                                ->label('Field Type')
                                ->options([
                                    'text' => 'Text',
                                    'email' => 'Email',
                                    'number' => 'Number',
                                    'date' => 'Date',
                                ])
                                ->required(),
                            \Filament\Forms\Components\Toggle::make('required')
                                ->label('Required')
                                ->default(true),
                        ])
                        ->columnSpanFull(),
                ]),
            ]);
    }

    protected function fillForm(): void
    {
        // Load the visitorForm relationship
        $this->record->load('visitorForm');

        // Fill the form with the fields data from visitorForm
        $this->form->fill([
            'fields' => $this->record->visitorForm->fields ?? [],
        ]);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->visitorForm->update(['fields' => $data['fields']]);
        return parent::handleRecordUpdate($record, $data);
    }


    // protected function afterSave(): void
    // {
    //     $this->record->visitorForm->save();
    // }
}
