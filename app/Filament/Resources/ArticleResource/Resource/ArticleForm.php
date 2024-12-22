<?php

namespace App\Filament\Resources\ArticleResource\Resource;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Group;
use Illuminate\Support\Str;

class ArticleForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->columnSpan(['lg' => 2])
                    ->schema([
                        Forms\Components\Tabs::make('Article')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn(string $state, Forms\Set $set) => $set('slug', Str::slug($state))),

                                        Forms\Components\TextInput::make('slug')
                                            ->disabled()
                                            ->dehydrated()
                                            ->required(),

                                        Forms\Components\Textarea::make('description')
                                            ->required()
                                            ->columnSpanFull()
                                            ->rows(4),

                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->columnSpanFull()
                                            ->label(__('articles.form.published_date')),
                                    ])->columns(2),

                                Forms\Components\Tabs\Tab::make('Content')
                                    ->schema([
                                        Forms\Components\RichEditor::make('content')
                                            ->required(),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]),

                Group::make()
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        Section::make('Featured Image')
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->image()
                                    ->directory('articles')
                                    ->columnSpanFull(),
                            ]),

                        Section::make(__('articles.categories.plural'))
                            ->schema([
                                Forms\Components\Select::make('categories')
                                    ->multiple()
                                    ->relationship('categories', 'name')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn(string $state, Forms\Set $set) => $set('slug', Str::slug($state))),

                                        Forms\Components\TextInput::make('slug')
                                            ->disabled()
                                            ->dehydrated()
                                            ->required(),
                                    ])
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ])
            ->columns(['lg' => 3]);
    }
}
