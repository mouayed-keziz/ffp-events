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
use Illuminate\Database\Eloquent\Builder;

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

    public function getBreadcrumbs(): array
    {
        return [
            static::getResource()::getUrl() => __('panel/breadcrumbs.events'),
            static::getResource()::getUrl("view", ["record" => $this->getRecord()]) => $this->getRecord()->name ?? $this->getRecord()->title,
            __('panel/breadcrumbs.visitor_registrations'),
        ];
    }

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
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('displayName')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('anonymous_email', 'like', "%{$search}%")
                            ->orWhereHas('visitor', function (Builder $q) use ($search): Builder {
                                return $q->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                            });
                    })
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
                Tables\Filters\SelectFilter::make('submission_type')
                    ->options([
                        'authenticated' => __("panel/visitor_submissions.submission_type.authenticated"),
                        'anonymous' => __("panel/visitor_submissions.submission_type.anonymous"),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] === 'authenticated',
                            fn(Builder $query) => $query->whereNotNull('visitor_id'),
                        )->when(
                            $data['value'] === 'anonymous',
                            fn(Builder $query) => $query->whereNull('visitor_id'),
                        );
                    })
                    ->label(__("panel/visitor_submissions.fields.submission_type")),
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
                    ->options([
                        'event_id' => $this->getOwnerRecord()->id,
                    ])
                    ->disabled(true)
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
