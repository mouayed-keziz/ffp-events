<?php

namespace App\Filament\Resources\ExhibitorResource\RelationManagers;

use App\Enums\ExhibitorSubmissionStatus;
use App\Filament\Resources\ExhibitorSubmissionResource;
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
        return __('panel/exhibitor_submission.resource.plural_label');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms are disabled because exhibitor submissions are usually created through events
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('eventAnnouncement.title')
                    ->searchable()
                    ->sortable()
                    ->label(__('panel/exhibitor_submission.fields.event')),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label(__('panel/exhibitor_submission.fields.status')),
                Tables\Columns\TextColumn::make('total_prices.DZD')
                    ->sortable()
                    ->toggleable()
                    ->badge()
                    ->label('DZD')
                    ->formatStateUsing(fn($state) => $state ? number_format((float)$state, 2) . ' DZD' : '-'),
                Tables\Columns\TextColumn::make('total_prices.EUR')
                    ->sortable()
                    ->toggleable()
                    ->badge()
                    ->label('EUR')
                    ->formatStateUsing(fn($state) => $state ? number_format((float)$state, 2) . ' €' : '-'),
                Tables\Columns\TextColumn::make('total_prices.USD')
                    ->sortable()
                    ->toggleable()
                    ->badge()
                    ->label('USD')
                    ->formatStateUsing(fn($state) => $state ? number_format((float)$state, 2) . ' $' : '-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__('panel/exhibitor_submission.fields.created_at')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(ExhibitorSubmissionStatus::class)
                    ->label(__('panel/exhibitor_submission.filters.status')),
            ])
            ->headerActions([
                // Creating submissions directly is disabled
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn($record) => ExhibitorSubmissionResource::getUrl('view', ['record' => $record])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
