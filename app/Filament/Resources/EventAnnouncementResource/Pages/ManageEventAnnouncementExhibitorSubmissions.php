<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Enums\ExhibitorSubmissionStatus;
use App\Filament\Exports\ExhibitorSubmissionExporter;
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

class ManageEventAnnouncementExhibitorSubmissions extends ManageRelatedRecords
{
    use NestedPage;
    use NestedRelationManager;
    use HasPageSidebar;

    protected function getHeaderWidgets(): array
    {
        return [
            // EventVisitorSubmissionsChart::class,
            EventExhibitorSubmissionsChart::class,
        ];
    }

    protected static string $resource = EventAnnouncementResource::class;
    protected static string $relationship = 'exhibitorSubmissions';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public function getBreadcrumbs(): array
    {
        return [
            static::getResource()::getUrl() => __('panel/breadcrumbs.events'),
            static::getResource()::getUrl("view", ["record" => $this->getRecord()]) => $this->getRecord()->name ?? $this->getRecord()->title,
            __('panel/breadcrumbs.exhibitor_registrations'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __("panel/exhibitor_submission.resource.plural_label");
    }

    public function getTitle(): string
    {
        return __("panel/exhibitor_submission.resource.plural_label");
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('exhibitor.email')
                    ->searchable()
                    ->sortable()
                    ->label(__("panel/exhibitor_submission.fields.exhibitor")),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label(__("panel/exhibitor_submission.fields.status")),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__("panel/exhibitor_submission.fields.created_at")),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(ExhibitorSubmissionStatus::class)
                    ->label(__("panel/exhibitor_submission.fields.status")),
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(ExhibitorSubmissionExporter::class)
                    ->options([
                        'event_id' => $this->getOwnerRecord()->id,
                    ])
                    // ->label(__('Export Exhibitor Submissions'))
                    ->icon('heroicon-o-arrow-down-tray')
                // ->color('success'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
