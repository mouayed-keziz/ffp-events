<?php

namespace App\Filament\Exports;

use App\Filament\Exports\Traits\HasDynamicColumns;
use App\Models\VisitorSubmission;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class VisitorSubmissionExporter extends Exporter
{
    use HasDynamicColumns;

    protected static ?string $model = VisitorSubmission::class;
    public static ?int $eventId = null;

    protected function getDynamicColumns(?int $eventId): array
    {
        return [
            // ExportColumn::make('visitor_special_col')
            //     ->label('Visitor Special Column')
            //     ->state(fn(VisitorSubmission $record) => "Special visitor data for event {$eventId}"),
            // ExportColumn::make('visitor_event_info')
            //     ->label('Visitor Event Info')
            //     ->state(fn(VisitorSubmission $record) => "Visitor {$record->visitor->name} in event {$eventId}"),
        ];
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('export_visitor_name')
                ->label('Visitor Name'),

            ExportColumn::make('export_visitor_email')
                ->label('Visitor Email'),

            ExportColumn::make('export_submission_type')
                ->label('Submission Type'),

            ExportColumn::make('eventAnnouncement.title')
                ->label('Event Title'),

            ExportColumn::make('export_status')
                ->label('Status'),

            // Badge information
            ExportColumn::make('export_has_badge')
                ->label('Has Badge'),

            ExportColumn::make('export_badge_id')
                ->label('Badge ID'),

            ExportColumn::make('export_badge_name')
                ->label('Badge Name'),

            ExportColumn::make('export_badge_email')
                ->label('Badge Email'),

            ExportColumn::make('export_badge_position')
                ->label('Badge Position'),

            ExportColumn::make('export_badge_company')
                ->label('Badge Company'),

            // Media attachments count
            ExportColumn::make('export_attachments_count')
                ->label('Number of Attachments'),

            ExportColumn::make('created_at')
                ->label('Submitted At'),

            ExportColumn::make('updated_at')
                ->label('Last Updated'),

            // Formatted answers column
            ExportColumn::make('export_formatted_answers')
                ->label('Formatted Answers'),

            // Optional JSON columns (uncomment if needed)
            // ExportColumn::make('export_answers_json')
            //     ->label('Answers (JSON)'),

            // ExportColumn::make('export_answers_json_readable')
            //     ->label('Answers (JSON - Readable)'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your visitor submission export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
