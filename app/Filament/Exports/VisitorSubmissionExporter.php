<?php

namespace App\Filament\Exports;

// use App\Filament\Exports\Traits\HasDynamicColumns;
use App\Models\VisitorSubmission;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class VisitorSubmissionExporter extends Exporter
{
    // use HasDynamicColumns;

    protected static ?string $model = VisitorSubmission::class;
    public static ?int $eventId = null;

    // protected function getDynamicColumns(?int $eventId): array
    // {
    //     return [
    //         // ExportColumn::make('visitor_special_col')
    //         //     ->label('Visitor Special Column')
    //         //     ->state(fn(VisitorSubmission $record) => "Special visitor data for event {$eventId}"),
    //         // ExportColumn::make('visitor_event_info')
    //         //     ->label('Visitor Event Info')
    //         //     ->state(fn(VisitorSubmission $record) => "Visitor {$record->visitor->name} in event {$eventId}"),
    //     ];
    // }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->enabledByDefault(false)
                ->label('ID'),

            ExportColumn::make('visitor.name')
                ->enabledByDefault(false)
                ->label('Visitor Name')
                ->state(fn(VisitorSubmission $record) => (!$record->isAnonymous()) ? $record->visitor->name : ($record->badge?->name ?? 'N/A')),

            ExportColumn::make('visitor.email')
                ->enabledByDefault(false)
                ->label('Visitor Email')
                ->state(fn(VisitorSubmission $record) => (!$record->isAnonymous()) ? $record->visitor->email : $record->anonymous_email),

            ExportColumn::make('type')
                ->enabledByDefault(false)
                ->label('Submission Type')
                ->state(fn(VisitorSubmission $record) => $record->isAnonymous() ? 'Anonyme' : 'Authentifié'),

            ExportColumn::make('eventAnnouncement.title')
                ->enabledByDefault(false)
                ->label('Event Title'),

            ExportColumn::make('status')
                ->enabledByDefault(false)
                ->label('Status')
                ->state(fn(VisitorSubmission $record) => $record->status ? $record->status->value : null),

            // Badge information
            ExportColumn::make('has_badge')
                ->enabledByDefault(false)
                ->label('Has Badge')
                ->state(fn(VisitorSubmission $record) => $record->badge ? 'Yes' : 'No'),

            ExportColumn::make('badge_id')
                ->enabledByDefault(false)
                ->label('Badge ID')
                ->state(fn(VisitorSubmission $record) => $record->badge?->id),

            ExportColumn::make('badge.name')
                ->enabledByDefault(false)
                ->label('Badge Name'),
            // ->state(fn(VisitorSubmission $record) => $record->badge?->name),

            ExportColumn::make('badge.email')
                ->enabledByDefault(false)
                ->label('Badge Email'),
            // ->state(fn(VisitorSubmission $record) => $record->badge?->email),

            ExportColumn::make('badge.position')
                ->enabledByDefault(false)
                ->label('Badge Position'),
            // ->state(fn(VisitorSubmission $record) => $record->badge?->position),

            ExportColumn::make('badge.company')
                ->enabledByDefault(false)
                ->label('Badge Company'),
            // ->state(fn(VisitorSubmission $record) => $record->badge?->company),

            // Media attachments count
            // ExportColumn::make('attachments_count')
            //     ->label('Number of Attachments')
            //     ->state(fn(VisitorSubmission $record) => $record->getMedia('attachments')->count()),

            ExportColumn::make('created_at')
                ->enabledByDefault(false)
                ->label('Submitted At'),

            ExportColumn::make('updated_at')
                ->enabledByDefault(false)
                ->label('Last Updated'),

            // New formatted answers column
            ExportColumn::make('formatted_answers')
                ->enabledByDefault(false)
                ->label('Formatted Answers')
                ->state(fn(VisitorSubmission $record) => $record->getFormattedAnswersJsonAttribute()),

            // Commented out original JSON columns
            // ExportColumn::make('answers_json')
            //     ->label('Answers (JSON)')
            //     ->state(fn(VisitorSubmission $record) => json_encode($record->answers)),

            // ExportColumn::make('answers_json_readable')
            //     ->label('Answers (JSON - Readable)')
            //     ->state(fn(VisitorSubmission $record) => json_encode($record->answers, JSON_UNESCAPED_UNICODE)),
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
