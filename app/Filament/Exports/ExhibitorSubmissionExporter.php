<?php

namespace App\Filament\Exports;

use App\Models\ExhibitorSubmission;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ExhibitorSubmissionExporter extends Exporter
{
    protected static ?string $model = ExhibitorSubmission::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('exhibitor.name')
                ->label('Exhibitor Name'),

            ExportColumn::make('exhibitor.email')
                ->label('Exhibitor Email'),

            ExportColumn::make('eventAnnouncement.title')
                ->label('Event Title'),

            ExportColumn::make('status')
                ->label('Status')
                ->state(fn(ExhibitorSubmission $record) => $record->status ? $record->status->value : null),

            // Price columns
            ExportColumn::make('total_prices_dzd')
                ->label('Total Price (DZD)')
                ->state(fn(ExhibitorSubmission $record) => $record->total_prices['DZD'] ?? null),

            ExportColumn::make('total_prices_eur')
                ->label('Total Price (EUR)')
                ->state(fn(ExhibitorSubmission $record) => $record->total_prices['EUR'] ?? null),

            ExportColumn::make('total_prices_usd')
                ->label('Total Price (USD)')
                ->state(fn(ExhibitorSubmission $record) => $record->total_prices['USD'] ?? null),

            // Rejection reason in different languages (if rejected)
            ExportColumn::make('rejection_reason_ar')
                ->label('Rejection Reason (Arabic)')
                ->state(fn(ExhibitorSubmission $record) => $record->getTranslation('rejection_reason', 'ar')),

            ExportColumn::make('rejection_reason_fr')
                ->label('Rejection Reason (French)')
                ->state(fn(ExhibitorSubmission $record) => $record->getTranslation('rejection_reason', 'fr')),

            ExportColumn::make('rejection_reason_en')
                ->label('Rejection Reason (English)')
                ->state(fn(ExhibitorSubmission $record) => $record->getTranslation('rejection_reason', 'en')),

            ExportColumn::make('edit_deadline')
                ->label('Edit Deadline'),

            ExportColumn::make('update_requested_at')
                ->label('Update Requested At'),

            // Payment slices count
            ExportColumn::make('payment_slices_count')
                ->label('Number of Payment Slices')
                ->state(fn(ExhibitorSubmission $record) => $record->paymentSlices()->count()),

            // Valid payments count
            ExportColumn::make('valid_payments_count')
                ->label('Number of Valid Payments')
                ->state(fn(ExhibitorSubmission $record) => $record->paymentSlices()->where('status', 'valid')->count()),

            // Badges count
            ExportColumn::make('badges_count')
                ->label('Number of Badges')
                ->state(fn(ExhibitorSubmission $record) => $record->badges()->count()),

            ExportColumn::make('created_at')
                ->label('Submitted At'),

            ExportColumn::make('updated_at')
                ->label('Last Updated'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your exhibitor submission export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
