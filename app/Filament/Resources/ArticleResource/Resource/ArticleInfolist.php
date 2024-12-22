<?php

namespace App\Filament\Resources\ArticleResource\Resource;

use Filament\Infolists;
use Filament\Infolists\Infolist;

class ArticleInfolist
{
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('title'),
                Infolists\Components\TextEntry::make('description')
                    ->columnSpanFull(),
                Infolists\Components\TextEntry::make('content')
                    ->columnSpanFull(),
                Infolists\Components\TextEntry::make('published_at')
                    ->dateTime(),
                Infolists\Components\IconEntry::make('published')
                    ->boolean(),
            ]);
    }
}
