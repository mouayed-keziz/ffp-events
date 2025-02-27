<?php

namespace App\Filament\Resources\VisitorSubmissionResource\Components;

use App\Enums\FormField;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\View;

class SelectPricedDisplayBlock extends Block
{
    public static function make(string $name = 'select_priced'): static
    {
        return parent::make($name)
            ->icon(FormField::SELECT_PRICED->getIcon())
            ->schema([
                View::make('filament.forms.components.answer-display')
                    ->label(__("panel/visitor_submissions.field_answer"))
                    ->viewData([
                        'type' => 'select_priced',
                        'label' => function ($get) {
                            $label = $get('data.label');
                            return $label[app()->getLocale()] ?? '';
                        },
                        'answer' => function ($get) {
                            $answer = $get('answer');
                            $displayValue = $answer[app()->getLocale()] ?? '';
                            $price = $get('price') ?? null;

                            return $displayValue . ($price ? " - " . $price : "");
                        }
                    ]),
            ]);
    }
}
