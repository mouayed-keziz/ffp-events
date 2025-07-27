<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Exports\BadgeCheckLogsExporter;
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
use App\Enums\CheckInOutAction;

class ManageEventAnnouncementBadgeCheckLogs extends ManageRelatedRecords
{
    use NestedPage;
    use NestedRelationManager;
    use HasPageSidebar;

    protected static string $resource = EventAnnouncementResource::class;
    protected static string $relationship = 'badgeCheckLogs';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public function getBreadcrumbs(): array
    {
        return [
            static::getResource()::getUrl() => __('panel/breadcrumbs.events'),
            static::getResource()::getUrl("view", ["record" => $this->getRecord()]) => $this->getRecord()->name ?? $this->getRecord()->title,
            __('panel/breadcrumbs.badge_check_logs'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('panel/my_event.relation_managers.badge_check_logs.title');
    }

    public function getTitle(): string
    {
        return __('panel/my_event.relation_managers.badge_check_logs.title');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('badge_name')
            ->columns([
                Tables\Columns\TextColumn::make('badge_name')
                    ->label(__('panel/my_event.relation_managers.badge_check_logs.columns.badge_name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('badge_email')
                    ->label(__('panel/my_event.relation_managers.badge_check_logs.columns.badge_email'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('badge_company')
                    ->label(__('panel/my_event.relation_managers.badge_check_logs.columns.badge_company'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->limit(30),

                Tables\Columns\BadgeColumn::make('action')
                    ->label(__('panel/my_event.relation_managers.badge_check_logs.columns.action'))
                    // ->colors([
                    //     'success' => CheckInOutAction::CHECK_IN,
                    //     'danger' => CheckInOutAction::CHECK_OUT,
                    // ])
                    // ->formatStateUsing(function ($state) {
                    //     return $state ? $state->value : '';
                    // })
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('action_time')
                    ->label(__('panel/my_event.relation_managers.badge_check_logs.columns.action_time'))
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('checkedByUser.name')
                    ->label(__('panel/my_event.relation_managers.badge_check_logs.columns.checked_by_user'))
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->label(__('panel/my_event.relation_managers.badge_check_logs.filters.action'))
                    ->options([
                        CheckInOutAction::CHECK_IN->value => CheckInOutAction::CHECK_IN->getLabel(),
                        CheckInOutAction::CHECK_OUT->value => CheckInOutAction::CHECK_OUT->getLabel(),
                    ])
                    ->attribute('action'),

                Tables\Filters\Filter::make('action_date')
                    ->label(__('panel/my_event.relation_managers.badge_check_logs.filters.action_date'))
                    ->form([
                        Forms\Components\DatePicker::make('from_date')
                            ->label(__('panel/my_event.relation_managers.badge_check_logs.filters.from_date')),
                        Forms\Components\DatePicker::make('to_date')
                            ->label(__('panel/my_event.relation_managers.badge_check_logs.filters.to_date')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('action_time', '>=', $date),
                            )
                            ->when(
                                $data['to_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('action_time', '<=', $date),
                            );
                    }),

                Tables\Filters\Filter::make('company')
                    ->label(__('panel/my_event.relation_managers.badge_check_logs.filters.company'))
                    ->form([
                        Forms\Components\TextInput::make('company_name')
                            ->label(__('panel/my_event.relation_managers.badge_check_logs.filters.company_name')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['company_name'],
                            fn(Builder $query, $company): Builder => $query->where('badge_company', 'like', "%{$company}%"),
                        );
                    }),

                Tables\Filters\Filter::make('checked_by')
                    ->label(__('panel/my_event.relation_managers.badge_check_logs.filters.checked_by'))
                    ->form([
                        Forms\Components\Select::make('checked_by_user_id')
                            ->label(__('panel/my_event.relation_managers.badge_check_logs.filters.user'))
                            ->relationship('checkedByUser', 'name')
                            ->searchable()
                            ->preload(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['checked_by_user_id'],
                            fn(Builder $query, $userId): Builder => $query->where('checked_by_user_id', $userId),
                        );
                    }),
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->label(__('panel/my_event.relation_managers.badge_check_logs.actions.export'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(BadgeCheckLogsExporter::class),
            ])
            ->actions([
                // No view action as requested
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('action_time', 'desc')
            ->poll('30s')
            ->searchable()
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->emptyStateHeading('No badge check logs')
            ->emptyStateDescription('No badge scanning activity has been recorded for this event.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }
}
