<?php

namespace App\Filament\Resources;

use App\Enums\ExhibitorFormField;
use App\Filament\Resources\ExhibitorFormResource\Components;
use App\Filament\Resources\ExhibitorFormResource\Pages;
use App\Filament\Resources\ExhibitorFormResource\RelationManagers;
use App\Models\EventAnnouncement;
use App\Models\ExhibitorForm;
use Filament\Forms;
use Filament\Forms\Components\Builder as ComponentsBuilder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Spatie\MediaLibrary\Forms\Components\MediaLibraryFileUpload;
use Guava\FilamentNestedResources\Ancestor;
use Guava\FilamentNestedResources\Concerns\NestedResource;

// Add imports for new components
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Group;

class ExhibitorFormResource extends Resource
{
    use NestedResource;

    public static ?EventAnnouncement $eventAnnouncement = null;

    public static function getEventAnnouncement(): ?EventAnnouncement
    {
        if (is_null(static::$eventAnnouncement)) {
            if ($parentId = request()->route('eventAnnouncement')) {
                static::$eventAnnouncement = EventAnnouncement::find($parentId);
            } elseif ($recordId = request()->route('record')) {
                $exhibitorForm = ExhibitorForm::find($recordId);
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
            'exhibitorForms',
            'eventAnnouncement',
        );
    }

    protected static ?string $model = ExhibitorForm::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        // Use the new accessor for EventAnnouncement
        $currencies = static::getEventAnnouncement()?->currencies ?? [];

        return $form
            ->columns(5)
            ->schema([
                Section::make("Exhibitor Form Information")
                    ->columnSpan(3)
                    ->description("This is the information about the exhibitor form.")
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->translatable(),
                        TextInput::make('description')
                            ->translatable(),
                    ]),
                Section::make("image")
                    ->columnSpan(2)
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('images')
                            ->collection('images')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif']),
                    ]),
                Group::make()
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('sections')
                            ->collapsed()
                            ->collapsible()
                            ->addActionLabel('Add Section')
                            ->itemLabel(function ($state) {
                                return "Section" . ($state['title'] ? ": " . ($state['title'][app()->getLocale()] ?? '') : '');
                            })
                            ->schema([
                                TextInput::make('title')
                                    ->label('Section Title')
                                    ->required()
                                    ->translatable(),
                                ComponentsBuilder::make('fields')
                                    ->collapsed()
                                    ->collapsible()
                                    ->label("Fields")
                                    ->addActionLabel('Add Field')
                                    ->blocks([
                                        Components\InputBlock::make(ExhibitorFormField::INPUT->value)
                                            ->icon('heroicon-o-pencil')
                                            ->label(function ($state) {
                                                return ExhibitorFormField::INPUT->getLabel() . (isset($state['label']) && is_array($state['label']) && isset($state['label'][app()->getLocale()]) ? ": " . $state['label'][app()->getLocale()] : '');
                                            }),
                                        Components\SelectBlock::make(ExhibitorFormField::SELECT->value)
                                            ->icon('heroicon-o-bars-3')
                                            ->label(function ($state) {
                                                return ExhibitorFormField::SELECT->getLabel() . (isset($state['label']) && is_array($state['label']) && isset($state['label'][app()->getLocale()]) ? ": " . $state['label'][app()->getLocale()] : '');
                                            }),
                                        Components\CheckboxBlock::make(ExhibitorFormField::CHECKBOX->value)
                                            ->icon('heroicon-o-check-circle')
                                            ->label(function ($state) {
                                                return ExhibitorFormField::CHECKBOX->getLabel() . (isset($state['label']) && is_array($state['label']) && isset($state['label'][app()->getLocale()]) ? ": " . $state['label'][app()->getLocale()] : '');
                                            }),
                                        Components\RadioBlock::make(ExhibitorFormField::RADIO->value)
                                            ->icon('heroicon-o-check-circle')
                                            ->label(function ($state) {
                                                return ExhibitorFormField::RADIO->getLabel() . (isset($state['label']) && is_array($state['label']) && isset($state['label'][app()->getLocale()]) ? ": " . $state['label'][app()->getLocale()] : '');
                                            }),
                                        Components\UploadBlock::make(ExhibitorFormField::UPLOAD->value)
                                            ->icon('heroicon-o-arrow-up-on-square-stack')
                                            ->label(function ($state) {
                                                return ExhibitorFormField::UPLOAD->getLabel() . (isset($state['label']) && is_array($state['label']) && isset($state['label'][app()->getLocale()]) ? ": " . $state['label'][app()->getLocale()] : '');
                                            }),
                                        Components\SelectBlockPriced::make(ExhibitorFormField::SELECT_PRICED->value, $currencies)
                                            ->icon('heroicon-o-currency-dollar')
                                            ->label(function ($state) {
                                                return ExhibitorFormField::SELECT_PRICED->getLabel() . (isset($state['label']) && is_array($state['label']) && isset($state['label'][app()->getLocale()]) ? ": " . $state['label'][app()->getLocale()] : '');
                                            }),
                                        Components\CheckboxBlockPriced::make(ExhibitorFormField::CHECKBOX_PRICED->value, $currencies)
                                            ->icon('heroicon-o-currency-dollar')
                                            ->label(function ($state) {
                                                return ExhibitorFormField::CHECKBOX_PRICED->getLabel() . (isset($state['label']) && is_array($state['label']) && isset($state['label'][app()->getLocale()]) ? ": " . $state['label'][app()->getLocale()] : '');
                                            }),
                                        Components\RadioBlockPriced::make(ExhibitorFormField::RADIO_PRICED->value, $currencies)
                                            ->icon('heroicon-o-currency-dollar')
                                            ->label(function ($state) {
                                                return ExhibitorFormField::RADIO_PRICED->getLabel() . (isset($state['label']) && is_array($state['label']) && isset($state['label'][app()->getLocale()]) ? ": " . $state['label'][app()->getLocale()] : '');
                                            }),
                                        Components\EcommerceBlock::make(ExhibitorFormField::ECOMMERCE->value, $currencies)
                                            ->icon('heroicon-o-shopping-cart'),
                                        // ->label(function ($state) {
                                        //     return ExhibitorFormField::ECOMMERCE->getLabel() . (isset($state['label']) && is_array($state['label']) && isset($state['label'][app()->getLocale()]) ? ": " . $state['label'][app()->getLocale()] : '');
                                        // }),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('Image')->circular(),
                TextColumn::make('title')->label('Title'),
                TextColumn::make('eventAnnouncement.title')->label('Event'),
            ])
            ->filters([ /* ... */])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            // 'index' => Pages\ListExhibitorForms::route('/'),
            'create' => Pages\CreateExhibitorForm::route('/create'),
            'edit' => Pages\EditExhibitorForm::route('/{record}/edit'),
        ];
    }
}
