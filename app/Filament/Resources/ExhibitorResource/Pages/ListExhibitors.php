<?php

namespace App\Filament\Resources\ExhibitorResource\Pages;

use App\Filament\Resources\ExhibitorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListExhibitors extends ListRecords
{
    protected static string $resource = ExhibitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon("heroicon-o-user-plus"),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('panel/exhibitors.tabs.all'))
                ->modifyQueryUsing(
                    fn(Builder $query) => $query->whereNull('deleted_at')
                )
                ->badge(function () {
                    return $this->getModel()::query()
                        ->whereNull('deleted_at')
                        ->count();
                }),

            'deleted' => Tab::make(__('panel/exhibitors.tabs.deleted'))
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
