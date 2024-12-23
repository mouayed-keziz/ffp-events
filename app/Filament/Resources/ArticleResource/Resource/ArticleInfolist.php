<?php

namespace App\Filament\Resources\ArticleResource\Resource;

use Filament\Infolists;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;

class ArticleInfolist
{
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('Article')
                    ->tabs([
                        Tabs\Tab::make(__('articles.form.tabs.information'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        TextEntry::make('title')
                                            ->label(__('articles.form.title')),
                                        TextEntry::make('status')
                                            ->badge()
                                            ->label("articles.form.status")
                                        // ->color(fn(string $state): string => match ($state) {
                                        //     'published' => 'success',
                                        //     'draft' => 'gray',
                                        //     'pending' => 'warning',
                                        //     'deleted' => 'danger',
                                        // }),
                                        ,
                                        TextEntry::make('description')
                                            ->label(__('articles.form.description'))
                                            ->columnSpanFull(),
                                        TextEntry::make('published_at')
                                            ->label(__('articles.form.published_date'))
                                            ->dateTime(),
                                        TextEntry::make('categories.name')
                                            ->label(__('articles.categories.plural'))
                                            ->badge()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make(__('articles.form.tabs.content'))
                            ->schema([

                                Infolists\Components\ViewEntry::make('banner')
                                    ->view('components.article-banner')
                                    ->columnSpanFull(),

                                // SpatieMediaLibraryImageEntry::make('image')
                                //     ->label("")
                                //     ->collection('image')
                                //     ->square()
                                //     ->columnSpanFull(),

                                // TextEntry::make('content')
                                //     ->label("")
                                //     ->html()
                                //     ->extraAttributes(["class" => "prose"])
                                //     ->columnSpanFull(),

                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
