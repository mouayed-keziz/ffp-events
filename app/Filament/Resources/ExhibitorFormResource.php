<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExhibitorFormResource\Pages;
use App\Filament\Resources\ExhibitorFormResource\RelationManagers;
use App\Models\ExhibitorForm;
use Filament\Forms;
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

class ExhibitorFormResource extends Resource
{
    protected static ?string $model = ExhibitorForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("Exhibitor Form Information")
                    ->description("This is the information about the exhibitor form.")
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        Select::make('event_announcement_id')
                            ->relationship('eventAnnouncement', 'title')
                            ->required(),
                        SpatieMediaLibraryFileUpload::make('images')
                            ->collection('images')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif']),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->circular(),

                TextColumn::make('name')
                    ->label('Name'),

                TextColumn::make('eventAnnouncement.title')
                    ->label('Event'),
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
            'index' => Pages\ListExhibitorForms::route('/'),
            'create' => Pages\CreateExhibitorForm::route('/create'),
            'edit' => Pages\EditExhibitorForm::route('/{record}/edit'),
        ];
    }
}
