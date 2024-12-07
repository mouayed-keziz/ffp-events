<?php

namespace App\Filament\Resources\UserResource\Resource;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;

class UserTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->toggleable()
                    ->sortable()
                    ->searchable()
                    ->label(__('users.columns.name'))
                    ->default(__('users.empty_states.name'))
                    ->description(fn(User $record): string => $record->email)
                    ->wrap(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->toggleable()
                    ->sortable()
                    ->searchable()
                    ->label(__('users.columns.roles'))
                    ->badge()
                    ->default(__('users.empty_states.roles')),

                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable()
                    ->sortable()
                    ->label(__('users.columns.created_at'))
                    ->dateTime()
                    ->formatStateUsing(fn($state) => $state ? $state->diffForHumans() : null)
                    ->tooltip(fn($state) => $state ? $state->format('Y-m-d H:i:s') : null),

                Tables\Columns\ToggleColumn::make('verified_at')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable()
                    ->label(__('users.columns.verified_at'))
                    ->afterStateUpdated(function ($state, $record) {
                        $record->update(['verified_at' => $state ? now() : null]);
                    }),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
                    ->label(__('users.filters.trashed.label'))
                    ->placeholder(__('users.filters.trashed.placeholder')),
                SelectFilter::make('roles')
                    ->label(__('users.filters.roles.label'))
                    ->placeholder(__('users.filters.roles.placeholder'))
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable(),
                SelectFilter::make('verified')
                    ->label(__('users.filters.verification.label'))
                    ->placeholder(__('users.filters.verification.placeholder'))
                    ->options([
                        'verified' => __('users.filters.verification.verified'),
                        'unverified' => __('users.filters.verification.unverified'),
                    ])
                    ->attribute('verified_at')
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value']) {
                            'verified' => $query->whereNotNull('verified_at'),
                            'unverified' => $query->whereNull('verified_at'),
                            default => $query,
                        };
                    }),
            ])
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
            ->emptyStateHeading(__('users.empty_states.title'))
            ->emptyStateDescription(__('users.empty_states.description'));
    }
}
