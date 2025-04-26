<?php

namespace App\Filament\Resources\CategoryResource\Resource;

use App\Filament\Exports\CategoryExporter;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ExportBulkAction;

class CategoryTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    // ->label(__("panel/logs.actions.export.label"))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(CategoryExporter::class)
            ])
            // ->paginated(false)
            ->columns([
                TextColumn::make('name')
                    ->label(__('panel/articles.categories.fields.name'))
                    // ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(__('panel/articles.categories.fields.slug'))
                    // ->searchable()
                    ->sortable()
                    ->badge()
                    ->color("gray"),
                TextColumn::make('articles_count')
                    ->label(__('panel/articles.categories.articles'))
                    ->counts('articles')
                    ->sortable()
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                ExportBulkAction::make()
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(CategoryExporter::class),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);;
    }
}
