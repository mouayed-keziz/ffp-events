<?php

namespace App\Filament\Resources\AdminResource\Resource;

use App\Filament\Resources\AdminResource\Resource\AdminActions;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;

class AdminTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->toggleable()
                    ->sortable()
                    ->searchable()
                    ->label(__('panel/admins.columns.name'))
                    ->default(__('panel/admins.empty_states.name'))
                    ->description(fn(User $record) => $record->email)
                    ->wrap(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->toggleable()
                    ->sortable()
                    ->searchable()
                    ->label(__('panel/admins.columns.roles'))
                    ->badge()
                    ->default(__('panel/admins.empty_states.roles')),

                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable()
                    ->sortable()
                    ->label(__('panel/admins.columns.created_at'))
                    ->dateTime()
                    ->formatStateUsing(fn($state) => $state ? $state->diffForHumans() : null)
                    ->tooltip(fn($state) => $state ? $state->format('Y-m-d H:i:s') : null),

                Tables\Columns\ToggleColumn::make('verified_at')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable()
                    ->label(__('panel/admins.columns.verified_at'))
                    ->afterStateUpdated(function ($state, $record) {
                        $record->update(['verified_at' => $state ? now() : null]);
                    }),
            ])
            ->filters([
                // SelectFilter::make('roles')
                //     ->label(__('panel/admins.filters.roles.label'))
                //     ->placeholder(__('panel/admins.filters.roles.placeholder'))
                //     ->multiple()
                //     ->relationship('roles', 'name')
                //     ->preload()
                //     ->searchable(),

                SelectFilter::make('verified')
                    ->label(__('panel/admins.filters.verification.label'))
                    ->placeholder(__('panel/admins.filters.verification.placeholder'))
                    ->options([
                        'verified' => __('panel/admins.filters.verification.verified'),
                        'unverified' => __('panel/admins.filters.verification.unverified'),
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
                    ->label(__('panel/admins.filters.trashed.label'))
                    ->placeholder(__('panel/admins.filters.trashed.placeholder')),
            ])
            // ->filtersFormColumns(3)
            // ->filtersLayout(FiltersLayout::Modal)
            ->actions([
                Tables\Actions\ViewAction::make()->button(),
                AdminActions::regeneratePasswordTableAction(),
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
            ->emptyStateHeading(__('panel/admins.empty_states.title'))
            ->emptyStateDescription(__('panel/admins.empty_states.description'));
    }
}
