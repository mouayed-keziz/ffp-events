<?php

namespace App\Filament\Resources\VisitorResource\Resource;

use App\Enums\Role;
use App\Filament\Exports\VisitorExporter;
use App\Models\User;
use App\Models\Visitor;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;

class VisitorTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->visible(fn() => auth()->user()->hasRole(Role::SUPER_ADMIN->value))
                    // ->label(__("panel/logs.actions.export.label"))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(VisitorExporter::class)
            ])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->toggleable()
                    ->sortable()
                    ->searchable()
                    ->label(__('panel/visitors.columns.name'))
                    ->default(__('panel/visitors.empty_states.name'))
                    ->description(fn(Visitor $record) => $record->email)
                    ->wrap(),

                // Tables\Columns\TextColumn::make('roles.name')
                //     ->toggleable()
                //     ->sortable()
                //     ->searchable()
                //     ->label(__('panel/visitors.columns.roles'))
                //     ->badge()
                //     ->default(__('panel/visitors.empty_states.roles')),

                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable()
                    ->sortable()
                    ->toggledHiddenByDefault()
                    ->label(__('panel/visitors.columns.created_at'))
                    ->dateTime()
                    ->formatStateUsing(fn($state) => $state ? $state->diffForHumans() : null)
                    ->tooltip(fn($state) => $state ? $state->format('Y-m-d H:i:s') : null),

                Tables\Columns\ToggleColumn::make('verified_at')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable()
                    ->label(__('panel/visitors.columns.verified_at'))
                    ->afterStateUpdated(function ($state, $record) {
                        $record->update(['verified_at' => $state ? now() : null]);
                    }),
            ])
            ->filters([
                // SelectFilter::make('verified')
                //     ->label(__('panel/visitors.filters.verification.label'))
                //     ->placeholder(__('panel/visitors.filters.verification.placeholder'))
                //     ->options([
                //         'verified' => __('panel/visitors.filters.verification.verified'),
                //         'unverified' => __('panel/visitors.filters.verification.unverified'),
                //     ])
                //     ->attribute('verified_at')
                //     ->query(function (Builder $query, array $data): Builder {
                //         return match ($data['value']) {
                //             'verified' => $query->whereNotNull('verified_at'),
                //             'unverified' => $query->whereNull('verified_at'),
                //             default => $query,
                //         };
                //     }),
                // TrashedFilter removed
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->button(),
                VisitorActions::regeneratePasswordTableAction(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ExportBulkAction::make()
                        ->visible(fn() => auth()->user()->hasRole(Role::SUPER_ADMIN->value))
                        ->exporter(VisitorExporter::class),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ])->color('gray'),
            ])
            ->emptyStateHeading(__('panel/visitors.empty_states.title'))
            ->emptyStateDescription(__('panel/visitors.empty_states.description'));
    }
}
