<?php

namespace App\Filament\Resources\ExhibitorFormResource\Components;

use App\Enums\Currency;
use App\Filament\Resources\ExhibitorFormResource\Components\Core\DescriptionInput;
use App\Filament\Resources\ExhibitorFormResource\Components\Core\LabelInput;
use App\Filament\Resources\ExhibitorFormResource\Components\Core\PriceInput;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\Product;
use Filament\Forms\Components\KeyValue;
use Illuminate\Support\Facades\Lang;

class EcommerceBlock
{
    public static function make(string $name, $currencies = [])
    {
        $blocks = Lang::get('panel.forms.exhibitors.blocks');

        return Block::make($name)
            ->columns(2)
            ->schema([
                LabelInput::make(),
                DescriptionInput::make(),
                Repeater::make('products')
                    ->label(__('panel/forms.exhibitors.blocks.products'))
                    ->itemLabel(__('panel/forms.exhibitors.blocks.product'))
                    ->addActionLabel(__('panel/forms.exhibitors.blocks.add_product'))
                    ->columnSpanFull()
                    ->schema([
                        Select::make('product_id')
                            ->label(__('panel/forms.exhibitors.blocks.product'))
                            ->columnSpanFull()
                            ->options(function () {
                                return Product::all()->pluck('codename', 'id')->toArray();
                            })
                            ->required()
                            ->searchable()
                            ->preload(),
                        PriceInput::make()
                    ])
                    ->columns(2),
            ]);
    }
}
