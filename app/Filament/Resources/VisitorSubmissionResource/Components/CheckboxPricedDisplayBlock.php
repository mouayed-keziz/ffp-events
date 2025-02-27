<?php

namespace App\Filament\Resources\VisitorSubmissionResource\Components;

use App\Enums\FormField;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\View;

class CheckboxPricedDisplayBlock extends Block
{
    public static function make(string $name = 'checkbox_priced'): static
    {
        return parent::make($name)
            ->icon(FormField::CHECKBOX_PRICED->getIcon())
            ->schema([
                View::make('filament.forms.components.answer-display')
                    ->label(__("panel/visitor_submissions.field_answer"))
                    ->viewData([
                        'type' => 'checkbox_priced',
                        'label' => function ($get) {
                            $label = $get('data.label');
                            return $label[app()->getLocale()] ?? '';
                        },
                        'answer' => function ($get) {
                            $answer = $get('answer');

                            if (is_array($answer) && count($answer) > 0) {
                                $locale = app()->getLocale();
                                $selectedValues = [];

                                foreach ($answer as $option) {
                                    $text = isset($option['option']) ? ($option['option'][$locale] ?? '') : '';
                                    $price = isset($option['price']) ? " - " . $option['price'] : '';
                                    $selectedValues[] = $text . $price;
                                }

                                return implode(', ', $selectedValues);
                            }

                            return '';
                        }
                    ]),
            ]);
    }
}
