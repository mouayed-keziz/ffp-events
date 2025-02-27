<?php

namespace App\Filament\Resources\VisitorSubmissionResource\Components;

use App\Enums\FormField;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\View;

class RadioPricedDisplayBlock extends Block
{
    public static function make(string $name = 'radio_priced'): static
    {
        return parent::make($name)
            ->icon(FormField::RADIO_PRICED->getIcon())
            ->schema([
                View::make('filament.forms.components.answer-display')
                    ->label(__("panel/visitor_submissions.field_answer"))
                    ->viewData([
                        'type' => 'radio_priced',
                        'label' => function ($get) {
                            $label = $get('data.label');
                            return $label[app()->getLocale()] ?? '';
                        },
                        'answer' => function ($get) {
                            $answer = $get('answer');
                            $locale = app()->getLocale();

                            if (!empty($answer)) {
                                $text = isset($answer['option']) ? ($answer['option'][$locale] ?? '') : '';
                                $price = isset($answer['price']) ? " - " . $answer['price'] : '';
                                return $text . $price;
                            }

                            return '';
                        }
                    ]),
            ]);
    }
}
