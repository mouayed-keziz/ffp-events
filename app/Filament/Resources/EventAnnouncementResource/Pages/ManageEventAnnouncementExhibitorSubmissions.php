<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Enums\ExhibitorSubmissionStatus;
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
                    ->label(__("panel/visitor_submissions.fields.status")),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__("panel/visitor_submissions.fields.created_at")),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(ExhibitorSubmissionStatus::class)
                    ->label(__("panel/visitor_submissions.fields.status")),
            ])
            ->headerActions([])
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
