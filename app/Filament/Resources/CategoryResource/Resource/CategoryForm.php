<?php

namespace App\Filament\Resources\CategoryResource\Resource;

use App\Utils\SlugUtils;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(1)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('panel/articles.categories.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->translatable(),

                        Forms\Components\TextInput::make('slug')
                            ->prefix(config('app.url') . '/category/')
                            ->label(__('panel/articles.categories.fields.slug'))
                            ->required()
                            ->maxLength(255)
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('generateSlug')
                                    ->icon('heroicon-m-arrow-path')
                                    ->disabled(fn(Forms\Components\TextInput $component) => $component->isDisabled())
                                    ->action(function (Forms\Get $get, Forms\Set $set) {
                                        $set('slug', SlugUtils::generateSlugFromMultilingualName($get('name')));
                                    })
                            ),
                    ])
            ]);
    }
}
