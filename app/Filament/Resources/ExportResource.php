<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExportResource\Pages;
use App\Filament\Resources\ExportResource\RelationManagers;
use App\Models\Export;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class ExportResource extends Resource
{
    protected static ?string $model = Export::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';
    public static function getNavigationGroup(): ?string
    {
        return __('panel/nav.groups.management');
    }
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
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('file_name'),
                Tables\Columns\TextColumn::make('exporter'),
                Tables\Columns\TextColumn::make('processed_rows')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('total_rows')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('successful_rows')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('exported_by.name')
                    ->label('Exported By'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListExports::route('/'),
            // 'create' => Pages\CreateExport::route('/create'),
            // 'edit' => Pages\EditExport::route('/{record}/edit'),
        ];
    }
}
