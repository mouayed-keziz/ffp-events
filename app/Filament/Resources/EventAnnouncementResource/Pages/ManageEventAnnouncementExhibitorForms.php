<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Resources\EventAnnouncementResource;
use App\Filament\Resources\EventAnnouncementResource\Resource\ExhibitorFormDefinition;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Resources\RelationManagers\Concerns\Translatable;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageEventAnnouncementExhibitorForms extends ManageRelatedRecords
{
    use HasPageSidebar;
    // use Translatable;
    protected static string $resource = EventAnnouncementResource::class;
    protected static string $relationship = 'exhibitorForms';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Exhibitor Forms';
    }

    public function form(Form $form): Form
    {
        return ExhibitorFormDefinition::form($form);
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\LocaleSwitcher::make(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->slideOver() // Use slide-over for create action
                    ->mutateFormDataUsing(function (array $data): array {
                        return ExhibitorFormDefinition::cleanUpFormData($data);
                    }),
                Tables\Actions\AssociateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->slideOver(),
                Tables\Actions\EditAction::make()
                    ->slideOver() // Use slide-over for edit action
                    ->mutateFormDataUsing(function (array $data): array {
                        return ExhibitorFormDefinition::cleanUpFormData($data);
                    }),
                Tables\Actions\DissociateAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
