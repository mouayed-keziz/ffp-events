<?php

namespace App\Filament\Resources\VisitorSubmissionResource\Components;

use App\Enums\FormField;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\View;

class EcommerceDisplayBlock extends Block
{
    public static function make(string $name = 'ecommerce'): static
    {
        return parent::make($name)
            ->icon(FormField::ECOMMERCE->getIcon())
            ->schema([
                View::make('filament.forms.components.answer-display')
                    ->label(__("panel/visitor_submissions.field_answer"))
                    ->viewData([
                        'type' => 'ecommerce',
                        'label' => function ($get) {
                            $label = $get('data.label');
                            return $label[app()->getLocale()] ?? '';
                        },
                        'answer' => function ($get) {
                            $answer = $get('answer');

                            if (is_array($answer) && count($answer) > 0) {
                                $locale = app()->getLocale();
                                $selectedItems = [];

                                foreach ($answer as $item) {
                                    $name = isset($item['name']) ? ($item['name'][$locale] ?? '') : '';
                                    $quantity = isset($item['quantity']) ? " x" . $item['quantity'] : '';
                                    $price = isset($item['price']) ? " - " . $item['price'] : '';
                                    $selectedItems[] = $name . $quantity . $price;
                                }

                                return implode(', ', $selectedItems);
                            }

                            return '';
                        }
                    ]),
            ]);
    }
}
