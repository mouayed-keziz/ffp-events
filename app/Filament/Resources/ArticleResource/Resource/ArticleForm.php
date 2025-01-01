<?php

namespace App\Filament\Resources\ArticleResource\Resource;

use App\Filament\Resources\CategoryResource;
use App\Utils\SlugUtils;
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
                                Forms\Components\Tabs\Tab::make(__('panel/articles.form.tabs.information'))
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label(__('panel/articles.form.title'))
                                            ->placeholder(__('panel/articles.placeholders.title'))
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->translatable(),

                                        Forms\Components\Textarea::make('description')
                                            ->label(__('panel/articles.form.description'))
                                            ->placeholder(__('panel/articles.placeholders.description'))
                                            ->required()
                                            ->columnSpanFull()
                                            ->rows(4)
                                            ->translatable(),

                                        Forms\Components\TextInput::make('slug')
                                            ->prefix(config('app.url') . '/article/')
                                            ->label(__('panel/articles.form.slug'))
                                            ->placeholder(__('panel/articles.placeholders.slug'))
                                            ->required()
                                            ->maxLength(255)
                                            ->suffixAction(
                                                Forms\Components\Actions\Action::make('generateSlug')
                                                    ->icon('heroicon-m-arrow-path')
                                                    ->disabled(fn(Forms\Components\TextInput $component) => $component->isDisabled())
                                                    ->action(function (Forms\Get $get, Forms\Set $set) {
                                                        $set('slug', SlugUtils::generateSlugFromMultilingualName($get('title')));
                                                    })
                                            ),



                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->columnSpanFull()
                                            ->native(false)
                                            ->label(__('panel/articles.form.published_date')),
                                    ])->columns(1),

                                Forms\Components\Tabs\Tab::make(__('panel/articles.form.tabs.content'))
                                    ->schema([
                                        Forms\Components\RichEditor::make('content')
                                            ->label(__('panel/articles.form.content'))
                                            ->placeholder(__('panel/articles.placeholders.content'))
                                            ->required()
                                            ->translatable(),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]),

                Group::make()
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        Section::make(__('panel/articles.form.sections.featured_image'))
                            ->collapsible()
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('image')
                                    ->label("")
                                    ->image()
                                    ->imageEditor()
                                    ->collection('image')
                                    ->columnSpanFull(),
                            ]),

                        Section::make(__('panel/articles.categories.plural'))
                            ->schema([
                                Forms\Components\Select::make('categories')
                                    ->label("")
                                    ->multiple()
                                    ->native(false)
                                    ->preload()
                                    ->relationship('categories', 'name')
                                    ->createOptionForm(CategoryResource::form($form)->getComponents())
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
