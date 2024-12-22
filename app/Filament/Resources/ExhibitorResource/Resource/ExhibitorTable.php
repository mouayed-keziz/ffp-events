<?php

namespace App\Filament\Resources\ExhibitorResource\Resource;

use App\Filament\Resources\ExhibitorResource\Resource\ExhibitorActions;
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
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->toggleable()
                    ->sortable()
                    ->searchable()
                    ->label(__('exhibitors.columns.name'))
                    ->default(__('exhibitors.empty_states.name'))
                    ->description(fn(User $record): string => $record->email)
                    ->wrap(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->toggleable()
                    ->sortable()
                    ->searchable()
                    ->label(__('exhibitors.columns.roles'))
                    ->badge()
                    ->default(__('exhibitors.empty_states.roles')),

                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable()
                    ->sortable()
                    ->label(__('exhibitors.columns.created_at'))
                    ->dateTime()
                    ->formatStateUsing(fn($state) => $state ? $state->diffForHumans() : null)
                    ->tooltip(fn($state) => $state ? $state->format('Y-m-d H:i:s') : null),

                Tables\Columns\ToggleColumn::make('verified_at')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable()
                    ->label(__('exhibitors.columns.verified_at'))
                    ->afterStateUpdated(function ($state, $record) {
                        $record->update(['verified_at' => $state ? now() : null]);
                    }),
            ])
            ->filters([
                // SelectFilter::make('roles')
                //     ->label(__('exhibitors.filters.roles.label'))
                //     ->placeholder(__('exhibitors.filters.roles.placeholder'))
                //     ->multiple()
                //     ->relationship('roles', 'name')
                //     ->preload()
                //     ->searchable(),

                SelectFilter::make('verified')
                    ->label(__('exhibitors.filters.verification.label'))
                    ->placeholder(__('exhibitors.filters.verification.placeholder'))
                    ->options([
                        'verified' => __('exhibitors.filters.verification.verified'),
                        'unverified' => __('exhibitors.filters.verification.unverified'),
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
                    ->label(__('exhibitors.filters.trashed.label'))
                    ->placeholder(__('exhibitors.filters.trashed.placeholder')),
            ])
            // ->filtersFormColumns(3)
            // ->filtersLayout(FiltersLayout::Modal)
            ->actions([
                Tables\Actions\ViewAction::make()->button(),
                ExhibitorActions::regeneratePasswordTableAction(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ])->color('gray'),
            ])
            ->emptyStateHeading(__('exhibitors.empty_states.title'))
            ->emptyStateDescription(__('exhibitors.empty_states.description'));
    }
}
