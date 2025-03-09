<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Resources\EventAnnouncementResource;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentNestedResources\Concerns\NestedPage;
use Guava\FilamentNestedResources\Concerns\NestedRelationManager;

class ManageEventAnnouncementExhibitorSubmissions extends ManageRelatedRecords
{
    use NestedPage;
    use NestedRelationManager;
    use HasPageSidebar;

    protected static string $resource = EventAnnouncementResource::class;
    protected static string $relationship = 'exhibitorSubmissions';
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
                Tables\Columns\TextColumn::make('exhibitor.email')
                    ->searchable()
                    ->sortable()
                    ->label(__("panel/visitor_submissions.fields.visitor")),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
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
                // Tables\Actions\CreateAction::make()
                //     ->label(__("panel/visitor_submissions.actions.create")),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn($record) => EventAnnouncementResource::getUrl('exhibitor-submission.view', [
                        'record' => $record->eventAnnouncement->id,
                        'exhibitorSubmission' => $record->id,
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
