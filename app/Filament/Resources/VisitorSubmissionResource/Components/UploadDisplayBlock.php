<?php

namespace App\Filament\Resources\VisitorSubmissionResource\Components;

use App\Enums\FormField;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\View;

class UploadDisplayBlock extends Block
{
    public static function make(string $name = 'upload'): static
    {
        return parent::make($name)
            ->icon(FormField::UPLOAD->getIcon())
            ->schema([
                View::make('filament.forms.components.answer-display')
                    ->label("hhh")
                    ->viewData([
                        'type' => 'upload',
                        'label' => function ($get) {
                            $label = $get('data.label');
                            return $label[app()->getLocale()] ?? '';
                        },
                        'answer' => function ($get) {
                            return $get('answer') ?? '';
                        }
                    ]),
            ]);
    }
}
