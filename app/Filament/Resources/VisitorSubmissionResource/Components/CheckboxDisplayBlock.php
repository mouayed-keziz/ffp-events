<?php

namespace App\Filament\Resources\VisitorSubmissionResource\Components;

use App\Enums\FormField;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\View;

class CheckboxDisplayBlock extends Block
{
    public static function make(string $name = 'checkbox'): static
    {
        return parent::make($name)
            ->icon(FormField::CHECKBOX->getIcon())
            ->schema([
                View::make('filament.forms.components.answer-display')
                    ->label(__("panel/visitor_submissions.field_answer"))
                    ->viewData([
                        'type' => 'checkbox',
                        'label' => function ($get) {
                            $label = $get('data.label');
                            return $label[app()->getLocale()] ?? '';
                        },
                        'answer' => function ($get) {
                            $answer = $get('answer');
                            return is_array($answer) ? implode(', ', $answer) : $answer;
                        }
                    ]),
            ]);
    }
}
