<?php

namespace App\Filament\Resources\ArticleResource\Resource;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Group;
use Illuminate\Support\Str;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

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
                                Forms\Components\Tabs\Tab::make(__('articles.form.tabs.information'))
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label(__('articles.form.title'))
                                            ->placeholder(__('articles.placeholders.title'))
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn(string $state, Forms\Set $set) => $set('slug', Str::slug($state))),

                                        Forms\Components\TextInput::make('slug')
                                            ->label(__('articles.form.slug'))
                                            ->placeholder(__('articles.placeholders.slug'))
                                            ->disabled()
                                            ->dehydrated()
                                            ->required(),

                                        Forms\Components\Textarea::make('description')
                                            ->label(__('articles.form.description'))
                                            ->placeholder(__('articles.placeholders.description'))
                                            ->required()
                                            ->columnSpanFull()
                                            ->rows(4),

                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->columnSpanFull()
                                            ->native(false)
                                            ->label(__('articles.form.published_date')),
                                    ])->columns(2),

                                Forms\Components\Tabs\Tab::make(__('articles.form.tabs.content'))
                                    ->schema([
                                        Forms\Components\RichEditor::make('content')
                                            ->label(__('articles.form.content'))
                                            ->placeholder(__('articles.placeholders.content'))
                                            ->required(),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]),

                Group::make()
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        Section::make(__('articles.form.sections.featured_image'))
                            ->collapsible()
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('image')
                                    ->label("")
                                    ->image()
                                    ->imageEditor()
                                    ->collection('image')
                                    ->columnSpanFull(),
                            ]),

                        Section::make(__('articles.categories.plural'))
                            ->schema([
                                Forms\Components\Select::make('categories')
                                    ->label("")
                                    ->multiple()
                                    ->native(false)
                                    ->preload()
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

                                // Forms\Components\CheckboxList::make('categories')
                                //     ->searchable()
                                //     ->relationship('categories', 'name')
                            ]),
                    ]),
            ])
            ->columns(['lg' => 3]);
    }
}
