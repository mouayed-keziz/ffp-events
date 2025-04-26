<?php

namespace App\Filament\Resources\AdminResource\Resource;

use App\Filament\Exports\UserExporter;
use App\Filament\Resources\AdminResource\Resource\AdminActions;
use App\Models\User;
use App\Models\Role;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\ExportBulkAction;

class AdminTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    // ->label(__("panel/logs.actions.export.label"))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(UserExporter::class)
            ])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->toggleable()
                    ->sortable()
                    ->searchable()
                    ->label(__('panel/admins.columns.name'))
                    ->default(__('panel/admins.empty_states.name'))
                    ->description(fn(User $record) => $record->email)
                    ->wrap(),

                Tables\Columns\TextColumn::make('roles.formatted_name')
                    ->toggleable()
                    ->sortable()
                    ->searchable()
                    ->label(__('panel/admins.columns.roles'))
                    ->badge()
                    ->state(fn(User $record) => $record->roles->first()?->formatted_name)
                    ->color(fn(User $record) => $record->roles->first()?->color ?? 'gray')
                    ->icon(fn(User $record) => $record->roles->first()?->icon)
                    ->default(__('panel/admins.empty_states.roles')),

                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable()
                    ->sortable()
                    ->label(__('panel/admins.columns.created_at'))
                    ->dateTime()
                    ->formatStateUsing(fn($state) => $state ? $state->diffForHumans() : null)
                    ->tooltip(fn($state) => $state ? $state->format('Y-m-d H:i:s') : null),
            ])
            ->filters([
                // All filters removed as requested
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
                    ExportBulkAction::make()
                        ->icon('heroicon-o-arrow-down-tray')
                        ->exporter(UserExporter::class),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ])->color('gray'),
            ])
            ->emptyStateHeading(__('panel/admins.empty_states.title'))
            ->emptyStateDescription(__('panel/admins.empty_states.description'));
    }
}
