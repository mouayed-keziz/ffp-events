<?php

namespace App\Filament\Resources\EventAnnouncementResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ExhibitorSubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'exhibitorSubmissions';

    protected static ?string $title = 'Exhibitor Submissions';

    // public function getTitle(): ?string
    // {
    //     return __('exhibitor_submission.resource.plural_label');
    // }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->enum('status')
                    ->required()
                    ->label(__('exhibitor_submission.fields.status')),

                SpatieMediaLibraryFileUpload::make('attachments')
                    ->collection('attachments')
                    ->multiple()
                    ->downloadable()
                    ->label(__('exhibitor_submission.fields.attachments')),

                Forms\Components\Textarea::make('notes')
                    ->rows(3)
                    ->columnSpan('full'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('exhibitor.name')
                    ->label(__('exhibitor_submission.fields.exhibitor'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('exhibitor_submission.fields.status'))
                    ->badge(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('exhibitor_submission.fields.created_at'))
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\ViewColumn::make('total_prices')
                    ->label(__('exhibitor_submission.fields.total_prices'))
                    ->view('filament.tables.columns.prices'),

                Tables\Columns\TextColumn::make('media_count')
                    ->label(__('exhibitor_submission.fields.attachments'))
                    ->counts('media')
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(fn() => $this->getModel()::getModel()::getStatusOptions())
                    ->label(__('exhibitor_submission.filters.status')),

                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->label(__('exhibitor_submission.filters.date_range')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(__('exhibitor_submission.actions.view')),
                Tables\Actions\EditAction::make()
                    ->label(__('exhibitor_submission.actions.update')),
                Tables\Actions\DeleteAction::make()
                    ->label(__('exhibitor_submission.actions.delete')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
