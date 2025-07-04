<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Exports\VisitorSubmissionExporter;
use App\Filament\Resources\EventAnnouncementResource;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentNestedResources\Concerns\NestedPage;
use Guava\FilamentNestedResources\Concerns\NestedRelationManager;
use App\Filament\Resources\EventAnnouncementResource\Widgets\EventExhibitorSubmissionsChart;
use App\Filament\Resources\EventAnnouncementResource\Widgets\EventVisitorSubmissionsChart;

class ManageEventAnnouncementVisitorSubmissions extends ManageRelatedRecords
{
    use NestedPage;
    use NestedRelationManager;
    use HasPageSidebar;

    protected function getHeaderWidgets(): array
    {
        return [
            EventVisitorSubmissionsChart::class,
            // EventExhibitorSubmissionsChart::class,
        ];
    }

    protected static string $resource = EventAnnouncementResource::class;
    protected static string $relationship = 'visitorSubmissions';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function getNavigationLabel(): string
    {
        return __("panel/visitor_submissions.plural");
    }

    public function getTitle(): string
    {
        return __("panel/visitor_submissions.plural");
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('visitor.email')
                    ->searchable()
                    ->sortable()
                    ->label(__("panel/visitor_submissions.fields.visitor")),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label(__("panel/visitor_submissions.fields.status")),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__("panel/visitor_submissions.fields.created_at")),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => __("panel/visitor_submissions.status.pending"),
                        'approved' => __("panel/visitor_submissions.status.approved"),
                        'rejected' => __("panel/visitor_submissions.status.rejected"),
                    ])
                    ->label(__("panel/visitor_submissions.fields.status")),
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(VisitorSubmissionExporter::class)
                    // ->label(__('Export Visitor Submissions'))
                    ->icon('heroicon-o-arrow-down-tray')
                // ->color('success'),
                // Tables\Actions\CreateAction::make()
                //     ->label(__("panel/visitor_submissions.actions.create")),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn($record) => EventAnnouncementResource::getUrl('visitor-submission.view', [
                        'record' => $record->eventAnnouncement->id,
                        'visitorSubmission' => $record->id,
                    ])),
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
