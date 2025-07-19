<?php

namespace App\Filament\Resources\MyEventResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class CurrentAttendeesRelationManager extends RelationManager
{
    protected static string $relationship = 'currentAttendees';

    protected static ?string $title = null;

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('panel/my_event.relation_managers.current_attendees.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Read-only relation manager, no form needed
            ]);
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

                Tables\Columns\TextColumn::make('checkedInByUser.name')
                    ->label(__('panel/my_event.relation_managers.current_attendees.columns.checked_in_by_user'))
                    ->sortable()
                    ->toggleable(),

                // Tables\Columns\TextColumn::make('badge_code')
                //     ->label('Badge Code')
                //     ->searchable()
                //     ->sortable()
                //     ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('checked_in_date')
                    ->label(__('panel/my_event.relation_managers.current_attendees.filters.checked_in_date'))
                    ->form([
                        Forms\Components\DatePicker::make('from_date')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('to_date')
                            ->label('To Date'),
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
                            ->label('Company Name'),
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
                            ->label('Position'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['position_name'],
                            fn(Builder $query, $position): Builder => $query->where('badge_position', 'like', "%{$position}%"),
                        );
                    }),
            ])
            ->headerActions([
                // No header actions for read-only relation
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // No bulk actions for read-only relation
            ])
            ->defaultSort('checked_in_at', 'desc')
            ->poll('30s') // Auto-refresh every 30 seconds
            ->searchable()
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->emptyStateHeading('No attendees checked in')
            ->emptyStateDescription('No one has checked in to this event yet.')
            ->emptyStateIcon('heroicon-o-user-group');
    }
}
