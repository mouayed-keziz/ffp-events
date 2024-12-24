<?php

namespace App\Filament\Resources\CategoryResource\Resource;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class CategoryTable
{
    public static function table(Table $table): Table
    {
        return $table
            // ->paginated(false)
            ->columns([
                TextColumn::make('name')
                    ->label(__('articles.categories.fields.name'))
                    // ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(__('articles.categories.fields.slug'))
                    // ->searchable()
                    ->sortable()
                    ->badge()
                    ->color("gray"),
                TextColumn::make('articles_count')
                    ->label(__('articles.categories.articles'))
                    ->counts('articles')
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);;
    }
}
