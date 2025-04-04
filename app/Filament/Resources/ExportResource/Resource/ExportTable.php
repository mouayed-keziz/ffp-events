<?php

namespace App\Filament\Resources\ExportResource\Resource;

use App\Enums\ExportType;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExportTable
{
    public static function ExportColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')
                ->sortable()
                ->toggleable()
                ->label(__('panel/exports.columns.id'))
                ->placeholder('-'),

            Tables\Columns\TextColumn::make('exporter')
                ->searchable()
                ->sortable()
                ->toggleable()
                ->label(__('panel/exports.columns.exporter'))
                ->placeholder('-'),

            Tables\Columns\TextColumn::make('exported_by.name')
                ->searchable()
                ->sortable()
                ->toggleable()
                ->label(__('panel/exports.columns.exported_by'))
                ->url(
                    fn($record) => $record->exported_by->hasRole('admin')
                        ? route('filament.admin.resources.admins.view', ['record' => $record->exported_by])
                        : null,
                    shouldOpenInNewTab: true
                )
                ->placeholder('-'),

            Tables\Columns\TextColumn::make('file_name')
                ->searchable()
                ->sortable()
                ->toggleable()
                ->label(__('panel/exports.columns.file_name'))
                ->placeholder('-'),

            Tables\Columns\TextColumn::make('processed_rows')
                ->toggleable()
                ->sortable()
                ->label(__('panel/exports.columns.processed_rows'))
                ->badge()
                ->alignCenter()
                ->color('gray')
                ->placeholder('-'),

            Tables\Columns\TextColumn::make('total_rows')
                ->toggleable()
                ->sortable()
                ->label(__('panel/exports.columns.total_rows'))
                ->badge()
                ->alignCenter()
                ->color('info')
                ->placeholder('-'),

            Tables\Columns\TextColumn::make('successful_rows')
                ->toggleable()
                ->sortable()
                ->label(__('panel/exports.columns.successful_rows'))
                ->badge()
                ->alignCenter()
                ->color('success')
                ->placeholder('-'),

            Tables\Columns\TextColumn::make('completed_at')
                ->toggleable()
                ->sortable()
                ->tooltip(fn($record) => $record->completed_at->diffForHumans())
                ->label(__('panel/exports.columns.completed_at'))
                ->dateTime()
                ->placeholder('-'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::ExportColumns())
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->native(false)
                            ->label(__('panel/exports.filters.date.start'))
                            ->placeholder(__('panel/exports.filters.date.placeholder')),
                        Forms\Components\DatePicker::make('created_until')
                            ->native(false)
                            ->label(__('panel/exports.filters.date.end'))
                            ->placeholder(__('panel/exports.filters.date.placeholder')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                Tables\Filters\SelectFilter::make('exporter')
                    ->multiple()
                    ->label(__('panel/exports.filters.type.label'))
                    ->placeholder(__('panel/exports.filters.type.placeholder'))
                    ->options(ExportType::class)
            ])
            ->emptyStateHeading(__('panel/exports.empty.title'))
            ->emptyStateDescription(__('panel/exports.empty.description'))
            ->emptyStateIcon('heroicon-o-arrow-down-tray')
            ->actions([
                Tables\Actions\Action::make(strtoupper(ExportFormat::Csv->value))
                    ->label(strtoupper(ExportFormat::Csv->value))
                    ->color("success")
                    ->icon("heroicon-o-arrow-down-tray")
                    ->disabled(fn($record) => $record->completed_at === null)
                    ->tooltip(fn($record) => $record->completed_at !== null ? "not yet buddy" : null)
                    ->url(function ($record) {
                        return route('filament.exports.download', ['export' => $record, 'format' => ExportFormat::Csv], absolute: false);
                    }, shouldOpenInNewTab: true),
                Tables\Actions\Action::make(strtoupper(ExportFormat::Xlsx->value))
                    ->label(strtoupper(ExportFormat::Xlsx->value))
                    ->color("success")
                    ->icon("heroicon-o-arrow-down-tray")
                    ->url(function ($record) {
                        return route('filament.exports.download', ['export' => $record, 'format' => ExportFormat::Xlsx], absolute: false);
                    }, shouldOpenInNewTab: true),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
