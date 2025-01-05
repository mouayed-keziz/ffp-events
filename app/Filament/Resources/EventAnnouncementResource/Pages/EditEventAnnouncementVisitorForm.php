<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Enums\VisitorFormFieldType;
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
                        ->collapsible()
                        ->collapsed(true)
                        ->itemLabel(function ($state) {
                            $label = is_array($state['label'])
                                ? ($state['label'][app()->getLocale()] ?? array_values($state['label'])[0])
                                : $state['label'];
                            if ($state['type'] && VisitorFormFieldType::from($state['type'])) {
                                return VisitorFormFieldType::from($state['type'])->getLabel() . ' - ' . $label;
                            }
                            return $label;
                        })
                        ->label("")
                        ->schema([
                            \Filament\Forms\Components\Group::make()->columns(2)
                                ->schema([
                                    \Filament\Forms\Components\TextInput::make('label')
                                        ->label('field label')
                                        ->required()
                                        ->translatable(),
                                    \Filament\Forms\Components\TextInput::make('description')
                                        ->label('field description')
                                        ->translatable(),
                                ]),
                            \Filament\Forms\Components\Group::make()->columns(10)
                                ->schema([
                                    \Filament\Forms\Components\Select::make('type')
                                        ->label('Field Type')
                                        ->default(VisitorFormFieldType::Text->value)
                                        ->native(false)
                                        ->options(VisitorFormFieldType::class)
                                        ->required()
                                        ->columnSpan(8),
                                    \Filament\Forms\Components\Toggle::make('required')
                                        ->label('Required')
                                        ->default(true)
                                        ->inline(false)
                                        ->columnSpan(1),
                                ]),

                        ])
                        ->columnSpanFull(),
                ]),
            ]);
    }

    protected function fillForm(): void
    {
        $this->record->load('visitorForm');

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
