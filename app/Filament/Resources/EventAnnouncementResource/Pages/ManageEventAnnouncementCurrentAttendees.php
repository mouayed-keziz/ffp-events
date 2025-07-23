<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Exports\CurrentAttendeesExporter;
use App\Filament\Resources\EventAnnouncementResource;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentNestedResources\Concerns\NestedPage;
use Guava\FilamentNestedResources\Concerns\NestedRelationManager;
use Illuminate\Database\Eloquent\Builder;

class ManageEventAnnouncementCurrentAttendees extends ManageRelatedRecords
{
    use NestedPage;
    use NestedRelationManager;
    use HasPageSidebar;

    protected static string $resource = EventAnnouncementResource::class;
    protected static string $relationship = 'currentAttendees';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

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
