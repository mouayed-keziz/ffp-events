<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExportResource\Pages;
use App\Filament\Resources\ExportResource\RelationManagers;
use App\Models\Export;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class ExportResource extends Resource
{
    protected static ?string $model = Export::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static ?string $modelLabel = null;
    protected static ?string $pluralModelLabel = null;

    public static function getModelLabel(): string
    {
        return __('panel/exports.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel/exports.plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('panel/nav.groups.management');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('panel/exports.columns.id'))
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('completed_at')
                    ->tooltip(fn($record) => $record->completed_at->diffForHumans())
                    ->label(__('panel/exports.columns.completed_at'))
                    ->dateTime()
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('file_name')
                    ->label(__('panel/exports.columns.file_name'))
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('exporter')
                    ->label(__('panel/exports.columns.exporter'))
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('processed_rows')
                    ->label(__('panel/exports.columns.processed_rows'))
                    ->badge()
                    ->alignCenter()
                    ->color('gray')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('total_rows')
                    ->label(__('panel/exports.columns.total_rows'))
                    ->badge()
                    ->alignCenter()
                    ->color('info')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('successful_rows')
                    ->label(__('panel/exports.columns.successful_rows'))
                    ->badge()
                    ->alignCenter()
                    ->color('success')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('exported_by.name')
                    ->label(__('panel/exports.columns.exported_by'))
                    ->placeholder('-'),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label(__('panel/exports.filters.date.start'))
                            ->placeholder(__('panel/exports.filters.date.placeholder')),
                        Forms\Components\DatePicker::make('created_until')
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
                    })
            ])
            ->emptyStateHeading(__('panel/exports.empty.title'))
            ->emptyStateDescription(__('panel/exports.empty.description'))
            ->emptyStateIcon('heroicon-o-arrow-down-tray')
            ->actions([
                Tables\Actions\Action::make(strtoupper(ExportFormat::Csv->value))
                    ->label(strtoupper(ExportFormat::Csv->value))
                    ->color("success")
                    ->icon("heroicon-o-arrow-down-tray")
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExports::route('/'),
            // 'create' => Pages\CreateExport::route('/create'),
            // 'edit' => Pages\EditExport::route('/{record}/edit'),
        ];
    }
}
