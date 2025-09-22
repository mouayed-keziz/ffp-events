<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Enums\AttendeeStatus;
use App\Filament\Exports\CurrentAttendeesExporter;
use App\Filament\Resources\EventAnnouncementResource;
use App\Enums\CheckInOutAction;
use App\Enums\Role;
use App\Models\CurrentAttendee;
use App\Models\BadgeCheckLog;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Guava\FilamentNestedResources\Concerns\NestedPage;
use Guava\FilamentNestedResources\Concerns\NestedRelationManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ManageEventAnnouncementCurrentAttendees extends ManageRelatedRecords
{
    use NestedPage;
    use NestedRelationManager;
    use HasPageSidebar;

    protected static string $resource = EventAnnouncementResource::class;
    protected static string $relationship = 'currentAttendees';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public function getBreadcrumbs(): array
    {
        return [
            static::getResource()::getUrl() => __('panel/breadcrumbs.events'),
            static::getResource()::getUrl("view", ["record" => $this->getRecord()]) => $this->getRecord()->name ?? $this->getRecord()->title,
            __('panel/breadcrumbs.current_participants'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('panel/my_event.relation_managers.current_attendees.title');
    }

    public function getTitle(): string
    {
        return __('panel/my_event.relation_managers.current_attendees.title');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('badge_name')
            ->columns([
                Tables\Columns\TextColumn::make('badge_name')
                    ->label(__('panel/my_event.relation_managers.current_attendees.columns.badge_name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('badge_email')
                    ->label(__('panel/my_event.relation_managers.current_attendees.columns.badge_email'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('badge_company')
                    ->label(__('panel/my_event.relation_managers.current_attendees.columns.badge_company'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('badge_position')
                    ->label(__('panel/my_event.relation_managers.current_attendees.columns.badge_position'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('checked_in_at')
                    ->label(__('panel/my_event.relation_managers.current_attendees.columns.checked_in_at'))
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make("status")
                    ->badge()
                    ->label(__("panel/scanner.status")),

                Tables\Columns\TextColumn::make("last_check_in_at")
                    ->placeholder("-")
                    ->sortable()
                    ->toggleable()
                    ->label(__("panel/scanner.last_check_in_at")),

                Tables\Columns\TextColumn::make("total_time_spent_inside")
                    ->sortable()
                    ->toggleable()
                    ->formatStateUsing(function ($state, $record) {
                        return $record->formatted_total_time_spent;
                    })
                    ->label(__("panel/scanner.time_spent")),

                Tables\Columns\TextColumn::make('checkedInByUser.name')
                    ->label(__('panel/my_event.relation_managers.current_attendees.columns.checked_in_by_user'))
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->label(__('panel/scanner.attendance_status'))
                    ->options([
                        AttendeeStatus::INSIDE->value => AttendeeStatus::INSIDE->getLabel(),
                        AttendeeStatus::OUTSIDE->value => AttendeeStatus::OUTSIDE->getLabel(),
                    ])
                    ->attribute('status'),
                Tables\Filters\Filter::make('checked_in_date')
                    ->label(__('panel/my_event.relation_managers.current_attendees.filters.checked_in_date'))
                    ->form([
                        Forms\Components\DatePicker::make('from_date')
                            ->label(__('panel/my_event.relation_managers.current_attendees.filters.from_date')),
                        Forms\Components\DatePicker::make('to_date')
                            ->label(__('panel/my_event.relation_managers.current_attendees.filters.to_date')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('checked_in_at', '>=', $date),
                            )
                            ->when(
                                $data['to_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('checked_in_at', '<=', $date),
                            );
                    }),

                Tables\Filters\Filter::make('company')
                    ->label(__('panel/my_event.relation_managers.current_attendees.filters.company'))
                    ->form([
                        Forms\Components\TextInput::make('company_name')
                            ->label(__('panel/my_event.relation_managers.current_attendees.filters.company_name')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['company_name'],
                            fn(Builder $query, $company): Builder => $query->where('badge_company', 'like', "%{$company}%"),
                        );
                    }),

                Tables\Filters\Filter::make('position')
                    ->label(__('panel/my_event.relation_managers.current_attendees.filters.position'))
                    ->form([
                        Forms\Components\TextInput::make('position_name')
                            ->label(__('panel/my_event.relation_managers.current_attendees.filters.position_name')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['position_name'],
                            fn(Builder $query, $position): Builder => $query->where('badge_position', 'like', "%{$position}%"),
                        );
                    }),
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->label(__('panel/my_event.relation_managers.current_attendees.actions.export'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(CurrentAttendeesExporter::class),
                Tables\Actions\Action::make('adminCheckOutAll')
                    ->label('Admin Checkout All Inside')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->visible(fn() => Auth::user()->hasRole(Role::SUPER_ADMIN->value))
                    ->requiresConfirmation()
                    ->modalHeading('Admin Checkout All Inside')
                    ->modalDescription('This will check out all attendees currently inside. Are you sure?')
                    ->action(function () {
                        $event = $this->getRecord();
                        if (!$event) {
                            return;
                        }

                        $eventId = $event->id;
                        $userId = Auth::id();

                        DB::transaction(function () use ($eventId, $userId) {
                            CurrentAttendee::where('event_announcement_id', $eventId)
                                ->where('status', AttendeeStatus::INSIDE)
                                ->chunkById(500, function ($attendees) use ($eventId, $userId) {
                                    foreach ($attendees as $attendee) {
                                        // Update time spent before marking outside
                                        $attendee->updateTimeSpentOnCheckout();
                                        $attendee->status = AttendeeStatus::OUTSIDE;
                                        $attendee->save();

                                        BadgeCheckLog::create([
                                            'event_announcement_id' => $eventId,
                                            'badge_id' => $attendee->badge_id,
                                            'checked_by_user_id' => $userId,
                                            'action' => CheckInOutAction::ADMIN_CHECK_OUT,
                                            'action_time' => now(),
                                            'note' => 'Bulk admin checkout',
                                            'badge_code' => $attendee->badge_code,
                                            'badge_name' => $attendee->badge_name,
                                            'badge_email' => $attendee->badge_email,
                                            'badge_position' => $attendee->badge_position,
                                            'badge_company' => $attendee->badge_company,
                                        ]);
                                    }
                                });
                        });

                        Notification::make()
                            ->title('All inside attendees checked out')
                            ->success()
                            ->send();
                    })
                    ->disabled(fn() => !CurrentAttendee::where('event_announcement_id', $this->getRecord()->id)->where('status', AttendeeStatus::INSIDE)->exists()),
            ])
            ->actions([
                // No view action as requested
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('checked_in_at', 'desc')
            ->poll('30s')
            ->searchable()
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->emptyStateHeading('No attendees checked in')
            ->emptyStateDescription('No one has checked in to this event yet.')
            ->emptyStateIcon('heroicon-o-user-group');
    }
}
