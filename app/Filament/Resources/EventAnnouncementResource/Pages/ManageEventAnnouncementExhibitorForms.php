<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Resources\EventAnnouncementResource;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentNestedResources\Concerns\NestedPage;
use Guava\FilamentNestedResources\Concerns\NestedRelationManager;
use Guava\FilamentNestedResources\Concerns\NestedResource;


class ManageEventAnnouncementExhibitorForms extends ManageRelatedRecords
{
    use NestedPage;
    use NestedRelationManager;
    use HasPageSidebar;

    protected static string $resource = EventAnnouncementResource::class;

    protected static string $relationship = 'exhibitorForms';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __("panel/forms.exhibitors.plural");
    }

    public function getTitle(): string
    {
        return __("panel/forms.exhibitors.plural");
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label(__("panel/forms.exhibitors.images"))
                    ->placeholder('No image')
                    ->circular(),

                Tables\Columns\TextColumn::make('title')
                    ->label(__("panel/forms.exhibitors.title")),

                // Tables\Columns\TextColumn::make('eventAnnouncement.title')
                //     ->label('Event'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__("panel/forms.exhibitors.add_exhibitor_form")),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
