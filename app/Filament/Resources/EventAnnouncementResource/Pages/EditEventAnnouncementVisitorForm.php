<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Enums\FormField;
use App\Enums\Role;
use App\Filament\Resources\EventAnnouncementResource;
use App\Models\EventAnnouncement;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Guava\FilamentNestedResources\Concerns\NestedPage;
use Filament\Forms\Components\Builder as ComponentsBuilder;
use App\Filament\Resources\ExhibitorFormResource\Components;

class EditEventAnnouncementVisitorForm extends EditRecord
{
    use HasPageSidebar;
    use NestedPage;

    protected static string $resource = EventAnnouncementResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()->hasRole(Role::SUPER_ADMIN->value);
    }


    public function form(Form $form): Form
    {
        return $form->schema([
            \Filament\Forms\Components\Section::make()->schema([
                Repeater::make('sections')
                    ->collapsed()
                    ->collapsible()
                    ->addActionLabel(__("panel/forms.exhibitors.add_section"))
                    ->label(__("panel/forms.exhibitors.sections"))
                    ->itemLabel(function ($state) {
                        return __("panel/forms.exhibitors.section") . ($state['title'] ? ": " . ($state['title'][app()->getLocale()] ?? '') : '');
                    })
                    ->schema([
                        TextInput::make('title')
                            ->label(__("panel/forms.exhibitors.section_title_label"))
                            // TODO
                            // ->required()
                            ->translatable(),
                        ComponentsBuilder::make('fields')
                            ->collapsed()
                            ->collapsible()
                            ->label(__("panel/forms.exhibitors.fields"))
                            ->addActionLabel(__("panel/forms.exhibitors.add_field"))
                            ->blockNumbers(false)
                            ->blocks([
                                Components\InputBlock::make(FormField::INPUT->value)
                                    ->icon('heroicon-o-pencil')
                                    ->label(function ($state) {
                                        return FormField::INPUT->getLabel() . (isset($state['label']) && is_array($state['label']) && isset($state['label'][app()->getLocale()]) ? ": " . $state['label'][app()->getLocale()] : '');
                                    }),
                                Components\SelectBlock::make(FormField::SELECT->value)
                                    ->icon('heroicon-o-bars-3')
                                    ->label(function ($state) {
                                        return FormField::SELECT->getLabel() . (isset($state['label']) && is_array($state['label']) && isset($state['label'][app()->getLocale()]) ? ": " . $state['label'][app()->getLocale()] : '');
                                    }),
                                Components\CountrySelectBlock::make(FormField::COUNTRY_SELECT->value)
                                    ->icon('heroicon-o-globe-alt')
                                    ->label(function ($state) {
                                        return FormField::COUNTRY_SELECT->getLabel() . (isset($state['label']) && is_array($state['label']) && isset($state['label'][app()->getLocale()]) ? ": " . $state['label'][app()->getLocale()] : '');
                                    }),
                                Components\CheckboxBlock::make(FormField::CHECKBOX->value)
                                    ->icon('heroicon-o-check-circle')
                                    ->label(function ($state) {
                                        return FormField::CHECKBOX->getLabel() . (isset($state['label']) && is_array($state['label']) && isset($state['label'][app()->getLocale()]) ? ": " . $state['label'][app()->getLocale()] : '');
                                    }),
                                Components\RadioBlock::make(FormField::RADIO->value)
                                    ->icon('heroicon-o-check-circle')
                                    ->label(function ($state) {
                                        return FormField::RADIO->getLabel() . (isset($state['label']) && is_array($state['label']) && isset($state['label'][app()->getLocale()]) ? ": " . $state['label'][app()->getLocale()] : '');
                                    }),
                                Components\UploadBlock::make(FormField::UPLOAD->value)
                                    ->icon('heroicon-o-arrow-up-on-square-stack')
                                    ->label(function ($state) {
                                        return FormField::UPLOAD->getLabel() . (isset($state['label']) && is_array($state['label']) && isset($state['label'][app()->getLocale()]) ? ": " . $state['label'][app()->getLocale()] : '');
                                    }),
                            ]),
                    ]),
            ]),
        ]);
    }

    // New mount method to pre-fill form with visitorForm sections
    public function mount($record): void
    {
        parent::mount($record);
        $this->form->fill([
            'sections' => $this->record->visitorForm ? $this->record->visitorForm->sections : [],
        ]);
    }

    // Override the record update method to handle visitorForm saving
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record = parent::handleRecordUpdate($record, $data);

        $sections = $data['sections'] ?? [];

        if ($record->visitorForm) {
            $record->visitorForm->sections = $sections;
            $record->visitorForm->save();
        } else {
            $record->visitorForm()->create(['sections' => $sections]);
        }

        return $record;
    }
}
