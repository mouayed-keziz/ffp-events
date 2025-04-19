<?php

namespace App\Filament\Resources\LogResource\Resource;

use App\Enums\LogEvent;
use App\Enums\LogName;
use App\Filament\Exports\LogExporter;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;

class LogTable
{
    public static function LogColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')
                ->label("")
                ->sortable(),

            Tables\Columns\TextColumn::make('log_name')
                ->badge()
                ->searchable()
                ->sortable()
                ->toggleable()
                ->label(__('panel/logs.columns.log_name'))
                ->placeholder(__('panel/logs.empty_states.log_name')),

            Tables\Columns\TextColumn::make('causer.name')
                ->searchable()
                ->sortable()
                ->toggleable()
                ->label(__('panel/logs.columns.causer'))
                ->placeholder(__('panel/logs.empty_states.causer')),

            Tables\Columns\TextColumn::make('event')
                ->badge()
                ->sortable()
                ->toggleable()
                ->label(__('panel/logs.columns.event'))
                ->placeholder(__('panel/logs.empty_states.event')),

            Tables\Columns\TextColumn::make('subject.id')
                ->tooltip(fn($record) => $record->subjectField)
                ->limit(30)
                ->state(fn($record) => $record->subjectField)
                ->searchable()
                ->toggleable()
                ->label(__('panel/logs.columns.subject'))
                ->placeholder(__('panel/logs.empty_states.subject')),

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable()
                ->tooltip(fn($record) => $record->created_at->diffForHumans())
                ->label(__('panel/logs.columns.created_at'))
                ->placeholder(__('panel/logs.empty_states.created_at')),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns(self::LogColumns())
            ->defaultSort('created_at', 'desc')
            ->filtersFormColumns(2)
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filters([
                Filter::make('created_from')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label(__('panel/logs.filters.date.from'))
                            ->placeholder(__('panel/logs.filters.date.empty_state.from'))
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['created_from'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        );
                    }),

                Filter::make('created_until')
                    ->form([
                        Forms\Components\DatePicker::make('created_until')
                            ->label(__('panel/logs.filters.date.until'))
                            ->placeholder(__('panel/logs.filters.date.empty_state.until'))
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['created_until'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                    }),

                SelectFilter::make('log_name')
                    ->multiple()
                    ->label(__('panel/logs.filters.log_name'))
                    ->options(LogName::class)
                    ->native(false),

                SelectFilter::make('event')
                    ->multiple()
                    ->label(__('panel/logs.filters.event'))
                    ->options(LogEvent::class)
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->icon('heroicon-o-arrow-down-tray')
                        ->exporter(LogExporter::class),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
