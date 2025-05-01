<?php

namespace App\Filament\Resources\EventAnnouncementResource\Resource;

use App\Enums\Role;
use App\Filament\Exports\EventAnnouncementExporter;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use App\Models\EventAnnouncement;
use Filament\Tables\Actions\ExportBulkAction;

class EventAnnouncementTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    // ->label(__("panel/logs.actions.export.label"))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn() => auth()->user()->hasRole(Role::SUPER_ADMIN->value))
                    ->exporter(EventAnnouncementExporter::class)
            ])
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label(__('panel/event_announcement.fields.image'))
                    ->placeholder(__('panel/event_announcement.empty_states.photo'))
                    ->circular()
                    ->alignCenter()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('panel/event_announcement.fields.title'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('dates')
                    ->label(__('panel/event_announcement.fields.event_dates'))
                    ->state(function ($record): string {
                        $startDate = \Carbon\Carbon::parse($record->start_date)->format('d/m/y H:i');
                        $endDate = \Carbon\Carbon::parse($record->end_date)->format('d/m/y H:i');

                        return __('panel/event_announcement.fields.start_date') . ' ' . $startDate .
                            '<br>' .
                            __('panel/event_announcement.fields.end_date') . ' ' . $endDate;
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
                    ->label(__('panel/event_announcement.fields.location'))
                    ->limit(30)
                    ->placeholder(__('panel/event_announcement.empty_states.location'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),



                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('panel/event_announcement.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('panel/event_announcement.fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('panel/event_announcement.fields.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->placeholder(__('panel/event_announcement.empty_states.deleted_at'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
                    ->label(__('panel/event_announcement.filters.trashed')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('duplicate')
                    ->label(__('panel/event_announcement.actions.duplicate'))
                    ->visible(fn() => auth()->user()->hasRole(Role::SUPER_ADMIN->value))
                    ->icon('heroicon-o-document-duplicate')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading(fn(EventAnnouncement $record) => __('panel/event_announcement.modals.duplicate.heading', ['title' => $record->title]))
                    ->modalDescription(__('panel/event_announcement.modals.duplicate.description'))
                    ->modalSubmitActionLabel(__('panel/event_announcement.modals.duplicate.submit'))
                    ->action(function (EventAnnouncement $record): void {
                        // Create a new event announcement
                        $newEventAnnouncement = $record->duplicate();

                        // Notification of success
                        \Filament\Notifications\Notification::make()
                            ->title(__('panel/event_announcement.notifications.duplicated.title'))
                            ->success()
                            ->body(__('panel/event_announcement.notifications.duplicated.body'))
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->icon('heroicon-o-arrow-down-tray')
                        ->visible(fn() => auth()->user()->hasRole(Role::SUPER_ADMIN->value))
                        ->exporter(EventAnnouncementExporter::class),
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => auth()->check() && auth()->user()->hasRole(Role::SUPER_ADMIN->value))
                        ->label(__('panel/event_announcement.actions.delete')),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->visible(fn() => auth()->check() && auth()->user()->hasRole(Role::SUPER_ADMIN->value))
                        ->label(__('panel/event_announcement.actions.force_delete')),
                    Tables\Actions\RestoreBulkAction::make()
                        ->visible(fn() => auth()->check() && auth()->user()->hasRole(Role::SUPER_ADMIN->value))
                        ->label(__('panel/event_announcement.actions.restore')),
                ]),
            ]);
    }
}
