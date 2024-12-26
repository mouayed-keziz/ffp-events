<?php

namespace App\Filament\Resources\ArticleResource\Resource;

use Filament\Infolists;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Support\Enums\FontWeight;

class ArticleInfolist
{
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('Article')
                    ->tabs([
                        Tabs\Tab::make(__('panel/articles.form.tabs.information'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        TextEntry::make('title')
                                            ->size(TextEntrySize::Large)
                                            ->weight(FontWeight::Bold)
                                            ->label(__('panel/articles.form.title')),

                                        TextEntry::make('status')
                                            ->badge()
                                            ->label(__("panel/articles.columns.status")),

                                        TextEntry::make('description')
                                            ->label(__('panel/articles.form.description')),

                                        TextEntry::make('views')
                                            ->badge()
                                            ->label(__("panel/articles.columns.views"))
                                            ->color("gray"),

                                        TextEntry::make('published_at')
                                            ->label(__('panel/articles.form.published_date'))
                                            ->dateTime(),

                                        TextEntry::make('categories.name')
                                            ->label(__('panel/articles.categories.plural'))
                                            ->badge()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make(__('panel/articles.form.tabs.content'))
                            ->schema([

                                Infolists\Components\ViewEntry::make('article')
                                    ->view('panel.components.article-preview')
                                    ->columnSpanFull(),

                                // SpatieMediaLibraryImageEntry::make('image')
                                //     ->label("")
                                //     ->collection('image')
                                //     ->square()
                                //     ->columnSpanFull(),

                                // TextEntry::make('content')
                                //     ->label("")
                                //     ->html()
                                //     // ->extraAttributes(["class" => "prose prose-sm sm:prose-md lg:prose-lg dark:prose-invert max-w-none"])
                                //     ->columnSpanFull(),

                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
