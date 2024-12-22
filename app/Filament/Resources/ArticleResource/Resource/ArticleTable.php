<?php

namespace App\Filament\Resources\ArticleResource\Resource;

use App\Enums\ArticleStatus;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class ArticleTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->toggleable()
                    ->collection('image')
                    ->circular()
                    ->placeholder(__('articles.empty_states.image')),

                Tables\Columns\TextColumn::make('title')
                    ->label(trans('articles.columns.title'))
                    ->limit(25)
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label(trans('articles.columns.description'))
                    ->limit(25)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label(__("articles.columns.slug"))
                    ->badge()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color("gray"),

                Tables\Columns\TextColumn::make("categories.name")
                    ->label(__("articles.categories.plural"))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->limitList(1)
                    ->badge()
                    ->grow(true),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->alignCenter()
                    ->grow(true),

                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->label(trans('articles.form.published_date'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder(trans('articles.empty_states.published_at')),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label(trans('articles.columns.created_at'))
                    ->placeholder(trans('articles.empty_states.created_at'))
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label(trans('articles.columns.updated_at'))
                    ->placeholder(trans('articles.empty_states.updated_at'))
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->label(trans('articles.columns.deleted_at'))
                    ->placeholder(trans('articles.empty_states.deleted_at'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn($livewire) => ! $livewire->isDeletedTab()),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->visible(fn($livewire) => $livewire->isDeletedTab()),
                    Tables\Actions\RestoreBulkAction::make()
                        ->visible(fn($livewire) => $livewire->isDeletedTab()),
                ]),
            ]);
    }
}
