<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Enums\ArticleStatus;
use App\Filament\Resources\ArticleResource;
use App\Models\Article;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListArticles extends ListRecords
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon("heroicon-s-plus"),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->label(__('articles.status.all'))
                ->icon('heroicon-m-list-bullet')
                ->modifyQueryUsing(fn($query) => $query->withoutTrashed())
                ->badge(Article::published()->count())
                ->badgeColor(fn(): string => $this->activeTab === 'all' ? 'primary' : 'gray'),

            'published' => Tab::make()
                ->label(ArticleStatus::Published->getLabel())
                ->icon(ArticleStatus::Published->getIcon())
                ->modifyQueryUsing(fn($query) => $query->published()->withoutTrashed())
                ->badge(Article::published()->count())
                // ->badgeColor(fn(): string => $this->activeTab === 'published' ? ArticleStatus::Published->getColor() : 'gray')
                ->badgeColor(fn(): string => $this->activeTab === 'published' ? "primary" : 'gray'),

            'pending' => Tab::make()
                ->label(ArticleStatus::Pending->getLabel())
                ->icon(ArticleStatus::Pending->getIcon())
                ->modifyQueryUsing(fn($query) => $query->pending()->withoutTrashed())
                ->badge(Article::pending()->count())
                // ->badgeColor(fn(): string => $this->activeTab === 'pending' ? ArticleStatus::Pending->getColor() : 'gray')
                ->badgeColor(fn(): string => $this->activeTab === 'pending' ? "primary" : 'gray'),

            'draft' => Tab::make()
                ->label(ArticleStatus::Draft->getLabel())
                ->icon(ArticleStatus::Draft->getIcon())
                ->modifyQueryUsing(fn($query) => $query->draft()->withoutTrashed())
                ->badge(Article::draft()->count())
                // ->badgeColor(fn(): string => $this->activeTab === 'draft' ? ArticleStatus::Draft->getColor() : 'gray')
                ->badgeColor(fn(): string => $this->activeTab === 'draft' ? "primary" : 'gray'),

            'deleted' => Tab::make()
                ->label(ArticleStatus::Deleted->getLabel())
                ->icon(ArticleStatus::Deleted->getIcon())
                ->modifyQueryUsing(fn($query) => $query->onlyTrashed())
                ->badge(Article::onlyTrashed()->count())
                // ->badgeColor(fn(): string => $this->activeTab === 'deleted' ? ArticleStatus::Deleted->getColor() : 'gray')
                ->badgeColor(fn(): string => $this->activeTab === 'deleted' ? "primary" : 'gray'),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'all';
    }

    public function isDeletedTab(): bool
    {
        return $this->activeTab === 'deleted';
    }
}
