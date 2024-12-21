<?php

namespace App\Filament\Resources\EventAnnouncementResource\Resource;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventAnnouncementTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('event_announcement.fields.title'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('description')
                    ->label(__('event_announcement.fields.description'))
                    ->limit(40)
                    ->placeholder(__('event_announcement.empty_states.description'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->limit(50),

                Tables\Columns\TextColumn::make('dates')
                    ->label(__('event_announcement.fields.event_dates'))
                    ->state(function ($record): string {
                        $startDate = \Carbon\Carbon::parse($record->start_date)->format('d/m/y H:i');
                        $endDate = \Carbon\Carbon::parse($record->end_date)->format('d/m/y H:i');

                        return __('event_announcement.fields.start_date') . ' ' . $startDate .
                            '<br>' .
                            __('event_announcement.fields.end_date') . ' ' . $endDate;
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('start_date', 'like', "%{$search}%")
                            ->orWhere('end_date', 'like', "%{$search}%");
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('start_date', $direction);
                    })
                    ->toggleable()
                    ->html()
                    ->alignment(\Filament\Support\Enums\Alignment::Center),

                Tables\Columns\TextColumn::make('location')
                    ->label(__('event_announcement.fields.location'))
                    ->placeholder(__('event_announcement.empty_states.location'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\ImageColumn::make('image_path')
                    ->label(__('event_announcement.fields.image_path'))
                    ->placeholder(__('event_announcement.empty_states.photo'))
                    ->circular()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('max_exhibitors')
                    ->label(__('event_announcement.fields.max_exhibitors'))
                    ->alignment(\Filament\Support\Enums\Alignment::Center)
                    ->badge()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('max_visitors')
                    ->label(__('event_announcement.fields.max_visitors'))
                    ->alignment(\Filament\Support\Enums\Alignment::Center)
                    ->badge()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label(__('event_announcement.fields.is_featured'))
                    ->boolean()
                    ->alignment(\Filament\Support\Enums\Alignment::Center)
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\SelectColumn::make('status')
                    ->label(__('event_announcement.fields.status'))
                    ->options([
                        'draft' => __('event_announcement.filters.draft'),
                        'published' => __('event_announcement.filters.published'),
                        'archived' => __('event_announcement.filters.archived'),
                    ])
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('event_announcement.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('event_announcement.fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('event_announcement.fields.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->placeholder(__('event_announcement.empty_states.deleted_at'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('event_announcement.filters.status'))
                    ->options([
                        'draft' => __('event_announcement.filters.draft'),
                        'published' => __('event_announcement.filters.published'),
                        'archived' => __('event_announcement.filters.archived'),
                    ]),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label(__('event_announcement.filters.featured')),

                Tables\Filters\TrashedFilter::make()
                    ->label(__('event_announcement.filters.trashed')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('event_announcement.actions.delete')),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->label(__('event_announcement.actions.force_delete')),
                    Tables\Actions\RestoreBulkAction::make()
                        ->label(__('event_announcement.actions.restore')),
                ]),
            ]);
    }
}
