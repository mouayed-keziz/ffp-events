<?php

namespace App\Filament\Resources\VisitorSubmissionResource\Components;

use App\Enums\FormField;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Section;

class SubmissionAnswersDisplay extends Component
{
    protected string $view = 'filament.forms.components.submission-answers-display';

    public function getFormField($type)
    {
        return match ($type) {
            FormField::INPUT->value => InputDisplayBlock::make(),
            FormField::SELECT->value => SelectDisplayBlock::make(),
            FormField::CHECKBOX->value => CheckboxDisplayBlock::make(),
            FormField::RADIO->value => RadioDisplayBlock::make(),
            FormField::UPLOAD->value => UploadDisplayBlock::make(),
            FormField::SELECT_PRICED->value => SelectPricedDisplayBlock::make(),
            FormField::CHECKBOX_PRICED->value => CheckboxPricedDisplayBlock::make(),
            FormField::RADIO_PRICED->value => RadioPricedDisplayBlock::make(),
            FormField::ECOMMERCE->value => EcommerceDisplayBlock::make(),
            default => InputDisplayBlock::make(),
        };
    }

    public static function make(): static
    {
        return app(static::class);
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function getChildComponents(): array
    {
        return [
            Section::make()
                ->schema([
                    Builder::make('answers')
                        ->disabled()
                        ->hiddenLabel()
                        ->blocks([
                            Builder\Block::make('section')
                                ->schema([
                                    Builder::make('fields')
                                        ->disabled()
                                        ->hiddenLabel()
                                        ->blocks(function () {
                                            $blocks = [];
                                            foreach (FormField::cases() as $field) {
                                                $blocks[] = $this->getFormField($field->value);
                                            }
                                            return $blocks;
                                        }),
                                ]),
                        ])
                ])
        ];
    }
}
