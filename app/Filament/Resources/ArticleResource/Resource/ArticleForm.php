<?php

namespace App\Filament\Resources\ArticleResource\Resource;

use Filament\Forms;
use Filament\Forms\Form;

class ArticleForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('published_at')
                    ->label('Publish Date'),
            ]);
    }
}
