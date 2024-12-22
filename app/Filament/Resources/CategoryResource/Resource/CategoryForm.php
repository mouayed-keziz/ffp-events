<?php

namespace App\Filament\Resources\CategoryResource\Resource;

use Filament\Forms;
use Filament\Forms\Form;

class CategoryForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('articles.categories.fields.name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->label(__('articles.categories.fields.slug'))
                            ->required()
                            ->maxLength(255),
                    ])->columns(2)
            ]);
    }
}
