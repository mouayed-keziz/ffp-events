<?php

namespace App\Filament\Resources\ArticleResource\Resource;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class ArticleTable
{
    public static function ArticleColumns(): array
    {
        return [
            SpatieMediaLibraryImageColumn::make('image')
                ->toggleable()
                ->alignCenter()
                ->label(__("panel/articles.columns.image"))
                ->collection('image')
                ->circular()
                ->placeholder(__('panel/articles.empty_states.image')),

            Tables\Columns\TextColumn::make('title')
                ->label(__('panel/articles.columns.title'))
                ->limit(25)
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('description')
                ->label(__('panel/articles.columns.description'))
                ->limit(25)
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable(),

            Tables\Columns\TextColumn::make('slug')
                ->label(__("panel/articles.columns.slug"))
                ->badge()
                ->badge()
                ->toggleable(isToggledHiddenByDefault: true)
                ->color("gray"),

            Tables\Columns\TextColumn::make("categories.name")
                ->label(__("panel/articles.categories.plural"))
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable()
                ->limitList(1)
                ->badge()
                ->grow(true),

            Tables\Columns\TextColumn::make('status')
                ->label(__("panel/articles.columns.status"))
                ->badge()
                ->sortable()
                ->alignCenter()
                ->grow(true),

            Tables\Columns\TextColumn::make("views")
                ->label(__("panel/articles.columns.views"))
                ->badge()
                ->color("gray")
                ->alignCenter()
                ->sortable()
                ->toggleable(),
            // ->grow(true),

            Tables\Columns\TextColumn::make('published_at')
                ->dateTime()
                ->sortable()
                ->label(trans('panel/articles.form.published_date'))
                ->toggleable(isToggledHiddenByDefault: true)
                ->placeholder(trans('panel/articles.empty_states.published_at')),

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->label(trans('panel/articles.columns.created_at'))
                ->placeholder(trans('panel/articles.empty_states.created_at'))
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->label(trans('panel/articles.columns.updated_at'))
                ->placeholder(trans('panel/articles.empty_states.updated_at'))
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('deleted_at')
                ->dateTime()
                ->sortable()
                ->label(trans('panel/articles.columns.deleted_at'))
                ->placeholder(trans('panel/articles.empty_states.deleted_at'))
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }
    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns(self::ArticleColumns())
            ->filters([
                // Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                ArticleActions::PreviewAction(),
                Tables\Actions\ViewAction::make()->button(),
                Tables\Actions\EditAction::make()->button(),
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
