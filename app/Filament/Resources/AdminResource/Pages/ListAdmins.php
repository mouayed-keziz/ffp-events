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
                        ->whereHas('roles', fn(Builder $q) => $q->whereIn('name', [EnumsRole::ADMIN->value, EnumsRole::SUPER_ADMIN->value, EnumsRole::HOSTESS->value]))
                )
                ->icon(fn() => "heroicon-o-users")
                ->badge(function () {
                    return $this->getModel()::query()
                        ->whereNull('deleted_at')
                        ->whereHas('roles', fn(Builder $query) => $query->whereIn('name', [EnumsRole::ADMIN->value, EnumsRole::SUPER_ADMIN->value, EnumsRole::HOSTESS->value]))
                        ->count();
                }),

            'admin' => Tab::make(__('panel/admins.tabs.admin'))
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->whereNull('deleted_at')
                        ->whereHas('roles', fn(Builder $q) => $q->where('name', EnumsRole::ADMIN->value))
                )
                ->icon(fn() => EnumsRole::ADMIN->getIcon())
                ->badge(function () {
                    return $this->getModel()::query()
                        ->whereNull('deleted_at')
                        ->whereHas('roles', fn(Builder $query) => $query->where('name', EnumsRole::ADMIN->value))
                        ->count();
                }),

            'super_admin' => Tab::make(__('panel/admins.tabs.super_admin'))
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->whereNull('deleted_at')
                        ->whereHas('roles', fn(Builder $q) => $q->where('name', EnumsRole::SUPER_ADMIN->value))
                )
                ->icon(fn() => EnumsRole::SUPER_ADMIN->getIcon())
                ->badge(function () {
                    return $this->getModel()::query()
                        ->whereNull('deleted_at')
                        ->whereHas('roles', fn(Builder $query) => $query->where('name', EnumsRole::SUPER_ADMIN->value))
                        ->count();
                }),
            'hostess' => Tab::make(__('panel/admins.tabs.hostess'))
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->whereNull('deleted_at')
                        ->whereHas('roles', fn(Builder $q) => $q->where('name', EnumsRole::HOSTESS->value))
                )
                ->icon(fn() => EnumsRole::HOSTESS->getIcon())
                ->badge(function () {
                    return $this->getModel()::query()
                        ->whereNull('deleted_at')
                        ->whereHas('roles', fn(Builder $query) => $query->where('name', EnumsRole::HOSTESS->value))
                        ->count();
                }),
            'deleted' => Tab::make(__('panel/admins.tabs.deleted'))
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->onlyTrashed()
                        ->whereHas('roles', fn(Builder $q) => $q->whereIn('name', [EnumsRole::ADMIN->value, EnumsRole::SUPER_ADMIN->value, EnumsRole::HOSTESS->value]))
                )
                ->badge(function () {
                    return $this->getModel()::query()
                        ->onlyTrashed()
                        ->whereHas('roles', fn(Builder $query) => $query->whereIn('name', [EnumsRole::ADMIN->value, EnumsRole::SUPER_ADMIN->value, EnumsRole::HOSTESS->value]))
                        ->count();
                }),
        ];
    }
}
