<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Widgets\UserStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Widgets;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListUsers extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = UserResource::class;

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
}
