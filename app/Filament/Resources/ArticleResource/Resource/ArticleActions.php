<?php

namespace App\Filament\Resources\ArticleResource\Resource;

// use Filament\Actions\Action;
use Filament\Tables\Actions\Action;
use App\Enums\ArticleStatus;
use Filament\Actions\Action as HeaderAction;

class ArticleActions
{
    public static function getTableAction(): Action
    {
        return Action::make('preview')
            ->button()
            ->outlined()
            ->label(__("articles.actions.visit"))
            ->icon('heroicon-o-arrow-top-right-on-square')
            ->url(fn($record) => route('blog.show', $record), true)
            ->visible(fn($record) => $record->status === ArticleStatus::Published);
    }

    public static function getHeaderAction(): HeaderAction
    {
        return HeaderAction::make('preview')
            ->icon('heroicon-o-eye')
            ->color("gray")
            ->label(__("articles.actions.visit"))
            ->url(fn($record) => route('blog.show', $record), true)
            ->visible(fn($record) => $record->status === ArticleStatus::Published);
    }
}
