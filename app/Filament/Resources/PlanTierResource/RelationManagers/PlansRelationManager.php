<?php

namespace App\Filament\Resources\PlanTierResource\RelationManagers;

use App\Enums\Currency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlansRelationManager extends RelationManager
{
    protected static string $relationship = 'plans';

    public function form(Form $form): Form
    {
        $currencies = Currency::cases();
        $currencyFields = [];

        foreach ($currencies as $currency) {
            $currencyFields[] = Forms\Components\TextInput::make("price.{$currency->value}")
                ->label($currency->value)
                ->numeric()
                ->required()
                ->columnSpan(1);
        }

        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->translatable()
                    ->columnSpanFull()
                    ->label(__("panel/plan.title")),

                Forms\Components\RichEditor::make('content')
                    ->required()
                    ->translatable()
                    ->columnSpanFull()
                    ->label(__("panel/plan.content")),

                Forms\Components\Section::make(__("panel/plan.pricing"))
                    ->schema($currencyFields)
                    ->columns(count($currencyFields))
                    ->collapsible(),

                Forms\Components\Section::make(__("panel/plan.image"))
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                            ->label(false)
                            ->collection('image')
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('400')
                            ->imageResizeTargetHeight('400'),
                    ])
                    ->collapsible(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__("panel/plan.title")),
                Tables\Columns\ImageColumn::make('image')
                    ->label(__("panel/plan.image"))
                    ->circular(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
