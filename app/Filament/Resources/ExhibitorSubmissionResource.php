<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExhibitorSubmissionResource\Pages;
use App\Filament\Resources\ExhibitorSubmissionResource\RelationManagers;
use App\Filament\Resources\ExhibitorSubmissionResource\RelationManagers\PaymentSlicesRelationManager;
use App\Models\ExhibitorSubmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentNestedResources\Ancestor;
use Guava\FilamentNestedResources\Concerns\NestedResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExhibitorSubmissionResource extends Resource
{
    use NestedResource;
    public static function getAncestor(): ?Ancestor
    {
        return Ancestor::make(
            'exhibitorSubmissions',
            'eventAnnouncement',
        );
    }
    protected static ?string $model = ExhibitorSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // PaymentSlicesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExhibitorSubmissions::route('/'),
            // 'create' => Pages\CreateExhibitorSubmission::route('/create'),
            // 'edit' => Pages\EditExhibitorSubmission::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
