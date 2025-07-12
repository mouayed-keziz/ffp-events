<?php

namespace App\Filament\Resources;

use App\Enums\FormField;
use App\Filament\Resources\ExhibitorPostPaymentFormResource\Pages;
use App\Filament\Resources\ExhibitorPostPaymentFormResource\RelationManagers;
use App\Models\ExhibitorPostPaymentForm;
use Filament\Forms;
use Filament\Forms\Components\Builder as ComponentsBuilder;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ExhibitorFormResource\Components;
use App\Models\EventAnnouncement;
use Guava\FilamentNestedResources\Ancestor;
use Guava\FilamentNestedResources\Concerns\NestedResource;

class ExhibitorPostPaymentFormResource extends Resource
{
    use NestedResource;

    public static ?EventAnnouncement $eventAnnouncement = null;

    public static function getEventAnnouncement(): ?EventAnnouncement
    {
        if (is_null(static::$eventAnnouncement)) {
            if ($parentId = request()->route('eventAnnouncement')) {
                static::$eventAnnouncement = EventAnnouncement::find($parentId);
            } elseif ($recordId = request()->route('record')) {
                $exhibitorForm = ExhibitorPostPaymentForm::find($recordId);
                if ($exhibitorForm && $exhibitorForm->eventAnnouncement) {
                    static::$eventAnnouncement = $exhibitorForm->eventAnnouncement;
                }
            }
        }
        return static::$eventAnnouncement;
    }

    public static function getAncestor(): ?Ancestor
    {
        return Ancestor::make(
            'exhibitorPostPaymentForms',
            'eventAnnouncement',
        );
    }
    protected static ?string $model = ExhibitorPostPaymentForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $recordTitleAttribute = 'recordTitle';

    public static function getNavigationLabel(): string
    {
        return __("panel/forms.exhibitors.plural");
    }

    public static function getModelLabel(): string
    {
        return __("panel/forms.exhibitors.single");
    }

    public static function getPluralModelLabel(): string
    {
        return __("panel/forms.exhibitors.plural");
    }
    public static function form(Form $form): Form
    {
        return $form
            ->columns(5)
            ->schema([
                Section::make(__("panel/forms.exhibitors.section_title"))
                    ->columnSpan(3)
                    ->description(__("panel/forms.exhibitors.description"))
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->label(__("panel/forms.exhibitors.title"))
                            ->translatable(),
                        TextInput::make('description')
                            ->label(__("panel/forms.exhibitors.description"))
                            ->translatable(),
                    ]),
                Section::make(__("panel/forms.exhibitors.images"))
                    ->columnSpan(2)
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('images')
                            ->label("")
                            ->collection('images')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif']),
                    ]),
                Group::make()
                    ->columnSpanFull()
                    ->schema([
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
                                    ->required()
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label(__("panel/forms.exhibitors.images"))->circular(),
                TextColumn::make('title')->label(__("panel/forms.exhibitors.title")),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => Pages\CreateExhibitorPostPaymentForm::route('/create'),
            'edit' => Pages\EditExhibitorPostPaymentForm::route('/{record}/edit'),
        ];
    }
}
