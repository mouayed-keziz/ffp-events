<?php

namespace App\Filament\Resources\VisitorSubmissionResource\Components;

use App\Enums\FormField;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\View;

class SelectDisplayBlock extends Block
{
    public static function make(string $name = 'select'): static
    {
        return parent::make($name)
            ->icon(FormField::SELECT->getIcon())
            ->schema([
                View::make('filament.forms.components.answer-display')
                    ->label(__("panel/visitor_submissions.field_answer"))
                    ->viewData([
                        'type' => 'select',
                        'label' => function ($get) {
                            $label = $get('data.label');
                            return $label[app()->getLocale()] ?? '';
                        },
                        'answer' => function ($get) {
                            $answer = $get('answer');
                            return $answer[app()->getLocale()] ?? '';
                        }
                    ]),
            ]);
    }
}
