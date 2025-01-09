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
use Filament\Notifications\Notification;

class EditEventAnnouncementVisitorForm extends EditRecord
{
    use HasPageSidebar;
    protected static string $resource = EventAnnouncementResource::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            \Filament\Forms\Components\Section::make()->schema([
                \Filament\Forms\Components\Repeater::make('fields')
                    ->addActionLabel(__("panel/forms.add_field_action_label"))
                    ->collapsible()
                    ->collapsed(true)
                    ->itemLabel(fn($state) => $this->getFieldLabel($state))
                    ->label("")
                    ->schema([
                        \Filament\Forms\Components\Group::make()->columns(2)->schema([
                            \Filament\Forms\Components\TextInput::make('label')
                                ->label(__("panel/forms.field.label"))
                                ->required()
                                ->translatable(),
                            \Filament\Forms\Components\TextInput::make('description')
                                ->label(__("panel/forms.field.description"))
                                ->translatable(),
                        ]),
                        \Filament\Forms\Components\Group::make()->columns(10)->schema([
                            \Filament\Forms\Components\Select::make('type')
                                ->label(__("panel/forms.field.type"))
                                ->default(VisitorFormFieldType::Text->value)
                                ->native(false)
                                ->options(VisitorFormFieldType::class)
                                ->required()
                                ->live()
                                ->columnSpan(8),
                            \Filament\Forms\Components\Toggle::make('required')
                                ->label(__("panel/forms.field.required"))
                                ->default(true)
                                ->inline(false)
                                ->columnSpan(1),
                        ]),
                        \Filament\Forms\Components\Repeater::make('options')
                            ->addActionLabel(__("panel/forms.add_option_action_label"))
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn($state) => $this->getOptionLabel($state))
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('value')
                                    ->label(__("panel/forms.field.option"))
                                    ->required()
                                    ->translatable()
                            ])
                            ->columnSpanFull()
                            ->hidden(fn($get) => $this->shouldHideOptions($get)),
                    ])
                    ->columnSpanFull(),
            ]),
        ]);
    }

    protected function getFieldLabel(array $state): string
    {
        $label = is_array($state['label'])
            ? ($state['label'][app()->getLocale()] ?? array_values($state['label'])[0])
            : $state['label'];

        if ($state['type'] && VisitorFormFieldType::from($state['type'])) {
            return VisitorFormFieldType::from($state['type'])->getLabel() . ' - ' . $label;
        }

        return $label;
    }

    protected function getOptionLabel(array $state): string
    {
        $label = is_array($state['value'])
            ? ($state['value'][app()->getLocale()] ?? array_values($state['value'])[0])
            : $state['value'];

        return $label ?? __('panel/forms.field.option');
    }

    protected function shouldHideOptions($get): bool
    {
        if (null === $get('type')) {
            return true;
        }
        return !VisitorFormFieldType::from($get('type'))?->hasOptions();
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
        $this->validateFields($data['fields']);

        $processedFields = $this->processFields($data['fields']);

        $record->visitorForm->update(['fields' => $processedFields]);

        return parent::handleRecordUpdate($record, $data);
    }

    protected function validateFields(array $fields): void
    {
        collect($fields)->each(function ($field) {
            $fieldType = VisitorFormFieldType::from($field['type']);

            if ($fieldType->hasOptions() && empty($field['options'])) {
                Notification::make()
                    ->title(__('panel/forms.visitor.errors.options_required'))
                    ->danger()
                    ->send();
                $this->halt();
            }
        });
    }

    protected function processFields(array $fields): array
    {
        return collect($fields)->map(function ($field) {
            $fieldType = VisitorFormFieldType::from($field['type']);

            if (!$fieldType->hasOptions()) {
                unset($field['options']);
            } else {
                $field['options'] = $this->transformOptions($field['options']);
            }

            return $field;
        })->toArray();
    }

    protected function transformOptions(array $options): array
    {
        return collect($options)->map(function ($option) {
            return $option['value'];
        })->toArray();
    }
}
