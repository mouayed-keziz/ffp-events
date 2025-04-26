<?php

namespace App\Filament\Exports;

use App\Models\EventAnnouncement;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class EventAnnouncementExporter extends Exporter
{
    protected static ?string $model = EventAnnouncement::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            // Translatable fields with language versions

            ExportColumn::make('title_ar')
                ->label('Title (Arabic)')
                ->state(fn(EventAnnouncement $record) => $record->getTranslation('title', 'ar')),
            ExportColumn::make('title_fr')
                ->label('Title (French)')
                ->state(fn(EventAnnouncement $record) => $record->getTranslation('title', 'fr')),
            ExportColumn::make('title_en')
                ->label('Title (English)')
                ->state(fn(EventAnnouncement $record) => $record->getTranslation('title', 'en')),


            ExportColumn::make('description_ar')
                ->label('Description (Arabic)')
                ->state(fn(EventAnnouncement $record) => $record->getTranslation('description', 'ar')),
            ExportColumn::make('description_fr')
                ->label('Description (French)')
                ->state(fn(EventAnnouncement $record) => $record->getTranslation('description', 'fr')),
            ExportColumn::make('description_en')
                ->label('Description (English)')
                ->state(fn(EventAnnouncement $record) => $record->getTranslation('description', 'en')),


            ExportColumn::make('terms_ar')
                ->label('Terms (Arabic)')
                ->state(fn(EventAnnouncement $record) => $record->getTranslation('terms', 'ar')),
            ExportColumn::make('terms_fr')
                ->label('Terms (French)')
                ->state(fn(EventAnnouncement $record) => $record->getTranslation('terms', 'fr')),
            ExportColumn::make('terms_en')
                ->label('Terms (English)')
                ->state(fn(EventAnnouncement $record) => $record->getTranslation('terms', 'en')),

            ExportColumn::make('location')
                ->label('Location'),


            ExportColumn::make('content_ar')
                ->label('Content (Arabic)')
                ->state(fn(EventAnnouncement $record) => $record->getTranslation('content', 'ar')),
            ExportColumn::make('content_fr')
                ->label('Content (French)')
                ->state(fn(EventAnnouncement $record) => $record->getTranslation('content', 'fr')),
            ExportColumn::make('content_en')
                ->label('Content (English)')
                ->state(fn(EventAnnouncement $record) => $record->getTranslation('content', 'en')),

            // Non-translatable fields
            ExportColumn::make('start_date'),
            ExportColumn::make('end_date'),
            ExportColumn::make('visitor_registration_start_date'),
            ExportColumn::make('visitor_registration_end_date'),
            ExportColumn::make('exhibitor_registration_start_date'),
            ExportColumn::make('exhibitor_registration_end_date'),
            ExportColumn::make('website_url'),
            ExportColumn::make('contact'),
            ExportColumn::make('currencies'),

            // New count columns
            ExportColumn::make('exhibitor_forms_count')
                ->label('Number of Exhibitor Forms')
                ->state(fn(EventAnnouncement $record) => $record->exhibitorForms()->count()),

            ExportColumn::make('post_payment_forms_count')
                ->label('Number of Post Payment Forms')
                ->state(fn(EventAnnouncement $record) => $record->exhibitorPostPaymentForms()->count()),

            ExportColumn::make('visitor_submissions_count')
                ->label('Number of Visitor Submissions')
                ->state(fn(EventAnnouncement $record) => $record->visitorSubmissions()->count()),

            ExportColumn::make('exhibitor_submissions_count')
                ->label('Number of Exhibitor Submissions')
                ->state(fn(EventAnnouncement $record) => $record->exhibitorSubmissions()->count()),

            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your event announcement export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
