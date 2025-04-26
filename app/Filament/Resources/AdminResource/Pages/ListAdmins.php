<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Enums\Role as EnumsRole;
use App\Filament\Resources\AdminResource;
use App\Models\Role;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAdmins extends ListRecords
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon("heroicon-o-user-plus"),
        ];
    }
    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()->hasRole(EnumsRole::SUPER_ADMIN->value);
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('panel/admins.tabs.all'))
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->whereNull('deleted_at')
                        ->whereHas('roles', fn(Builder $q) => $q->whereIn('name', ['admin', 'super_admin']))
                )
                ->badge(function () {
                    return $this->getModel()::query()
                        ->whereNull('deleted_at')
                        ->whereHas('roles', fn(Builder $query) => $query->whereIn('name', ['admin', 'super_admin']))
                        ->count();
                }),

            'admin' => Tab::make(__('panel/admins.tabs.admin'))
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->whereNull('deleted_at')
                        ->whereHas('roles', fn(Builder $q) => $q->where('name', 'admin'))
                )
                ->badge(function () {
                    return $this->getModel()::query()
                        ->whereNull('deleted_at')
                        ->whereHas('roles', fn(Builder $query) => $query->where('name', 'admin'))
                        ->count();
                }),

            'super_admin' => Tab::make(__('panel/admins.tabs.super_admin'))
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->whereNull('deleted_at')
                        ->whereHas('roles', fn(Builder $q) => $q->where('name', 'super_admin'))
                )
                ->badge(function () {
                    return $this->getModel()::query()
                        ->whereNull('deleted_at')
                        ->whereHas('roles', fn(Builder $query) => $query->where('name', 'super_admin'))
                        ->count();
                }),

            'deleted' => Tab::make(__('panel/admins.tabs.deleted'))
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->onlyTrashed()
                        ->whereHas('roles', fn(Builder $q) => $q->whereIn('name', ['admin', 'super_admin']))
                )
                ->badge(function () {
                    return $this->getModel()::query()
                        ->onlyTrashed()
                        ->whereHas('roles', fn(Builder $query) => $query->whereIn('name', ['admin', 'super_admin']))
                        ->count();
                }),
        ];
    }
}
