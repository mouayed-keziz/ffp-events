<?php

namespace App\Filament\Resources\ExhibitorResource\Resource;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;

class ExhibitorTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->toggleable()
                    ->sortable()
                    ->searchable()
                    ->label(__('exhibitor.columns.name'))
                    ->default(__('exhibitor.empty_states.name'))
                    ->description(fn(User $record): string => $record->email)
                    ->wrap(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->toggleable()
                    ->sortable()
                    ->searchable()
                    ->label(__('exhibitor.columns.roles'))
                    ->badge()
                    ->default(__('exhibitor.empty_states.roles')),

                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable()
                    ->sortable()
                    ->label(__('exhibitor.columns.created_at'))
                    ->dateTime()
                    ->formatStateUsing(fn($state) => $state ? $state->diffForHumans() : null)
                    ->tooltip(fn($state) => $state ? $state->format('Y-m-d H:i:s') : null),

                Tables\Columns\ToggleColumn::make('verified_at')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable()
                    ->label(__('exhibitor.columns.verified_at'))
                    ->afterStateUpdated(function ($state, $record) {
                        $record->update(['verified_at' => $state ? now() : null]);
                    }),
            ])
            ->filters([
                // SelectFilter::make('roles')
                //     ->label(__('exhibitor.filters.roles.label'))
                //     ->placeholder(__('exhibitor.filters.roles.placeholder'))
                //     ->multiple()
                //     ->relationship('roles', 'name')
                //     ->preload()
                //     ->searchable(),

                SelectFilter::make('verified')
                    ->label(__('exhibitor.filters.verification.label'))
                    ->placeholder(__('exhibitor.filters.verification.placeholder'))
                    ->options([
                        'verified' => __('exhibitor.filters.verification.verified'),
                        'unverified' => __('exhibitor.filters.verification.unverified'),
                    ])
                    ->attribute('verified_at')
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value']) {
                            'verified' => $query->whereNotNull('verified_at'),
                            'unverified' => $query->whereNull('verified_at'),
                            default => $query,
                        };
                    }),

                Tables\Filters\TrashedFilter::make()
                    ->label(__('exhibitor.filters.trashed.label'))
                    ->placeholder(__('exhibitor.filters.trashed.placeholder')),
            ])
            // ->filtersFormColumns(3)
            // ->filtersLayout(FiltersLayout::Modal)
            ->actions([
                Tables\Actions\ViewAction::make()->iconButton(),
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
                Tables\Actions\ForceDeleteAction::make()->iconButton(),
                Tables\Actions\RestoreAction::make()->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ])->color('gray'),
            ])
            ->emptyStateHeading(__('exhibitor.empty_states.title'))
            ->emptyStateDescription(__('exhibitor.empty_states.description'));
    }
}
