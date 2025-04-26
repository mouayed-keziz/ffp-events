<?php

namespace App\Filament\Resources\VisitorResource\RelationManagers;

use App\Enums\SubmissionStatus;
use App\Filament\Resources\EventAnnouncementResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions';

    protected static ?string $recordTitleAttribute = 'id';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('panel/visitor_submissions.plural');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms are disabled because visitor submissions are usually created through events
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('eventAnnouncement.title')
                    ->searchable()
                    ->sortable()
                    ->label(__('panel/visitor_submissions.fields.event_announcement')),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn(SubmissionStatus $state): string => $state->value)
                    ->color(fn(SubmissionStatus $state): string => match ($state) {
                        SubmissionStatus::PENDING => 'warning',
                        SubmissionStatus::APPROVED => 'success',
                        SubmissionStatus::REJECTED => 'danger',
                        default => 'gray',
                    })
                    ->label(__('panel/visitor_submissions.fields.status')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__('panel/visitor_submissions.fields.created_at')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(SubmissionStatus::class)
                    ->label(__('panel/visitor_submissions.fields.status')),
            ])
            ->headerActions([
                // Creating submissions directly is disabled
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn($record) => EventAnnouncementResource::getUrl('visitor-submission.view', [
                        'record' => $record->eventAnnouncement->id,
                        'visitorSubmission' => $record->id,
                    ])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
