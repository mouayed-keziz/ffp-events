<?php

namespace App\Filament\Resources\VisitorResource\Pages;

use App\Filament\Resources\VisitorResource;
use App\Filament\Resources\VisitorResource\Widgets\UserStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Filament\Widgets;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Illuminate\Database\Eloquent\Builder;

class ListVisitors extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = VisitorResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            // UserStats::class,
            // Widgets\AccountWidget::class,
            // Widgets\FilamentInfoWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon("heroicon-o-user-plus"),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('panel/visitors.tabs.all'))
                ->modifyQueryUsing(
                    fn(Builder $query) => $query->whereNull('deleted_at')
                )
                ->badge(function () {
                    return $this->getModel()::query()
                        ->whereNull('deleted_at')
                        ->count();
                }),

            'deleted' => Tab::make(__('panel/visitors.tabs.deleted'))
                ->modifyQueryUsing(
                    fn(Builder $query) => $query->onlyTrashed()
                )
                ->badge(function () {
                    return $this->getModel()::query()
                        ->onlyTrashed()
                        ->count();
                }),
        ];
    }
}
