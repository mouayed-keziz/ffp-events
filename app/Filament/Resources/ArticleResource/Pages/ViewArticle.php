<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use App\Filament\Resources\ArticleResource\Resource\ArticleActions;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewArticle extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\EditAction::make()->icon("heroicon-o-pencil"),
            Actions\ForceDeleteAction::make()->icon("heroicon-o-trash"),
            Actions\RestoreAction::make()->icon("heroicon-o-arrow-path"),
            ArticleActions::getHeaderAction()
        ];
    }
}
