<?php

namespace App\Filament\Resources\VisitorResource\Pages;

use App\Filament\Resources\VisitorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVisitor extends EditRecord
{
    protected static string $resource = VisitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->icon("heroicon-o-eye"),
            Actions\DeleteAction::make()->icon("heroicon-o-trash"),
        ];
    }
}
