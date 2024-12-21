<?php

namespace App\Filament\Resources\VisitorResource\Pages;

use App\Filament\Resources\VisitorResource;
use App\Filament\Resources\VisitorResource\Widgets\UserStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Widgets;
use Filament\Pages\Concerns\ExposesTableToWidgets;

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
}
