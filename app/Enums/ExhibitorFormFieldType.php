<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ExhibitorFormFieldType: string implements HasLabel, HasColor
{
    case Text = 'text';
    case Number = 'number';
    case Email = 'email';
    case Phone = 'phone';
    case Date = 'date';
    case Paragraph = 'paragraph';
    case File = 'file';
    case Image = 'image';
    case Pdf = 'pdf';
    case SingleOption = 'single_option';
    case MultipleOptions = 'multiple_options';
    case Checkbox = 'checkbox';
    case Switch = 'switch';

    public function hasSinglePriceInput(): bool
    {
        return match ($this) {
            self::Switch, self::Checkbox  => true,
            default => false,
        };
    }

    public function hasMultiplePriceInput(): bool
    {
        return match ($this) {
            self::MultipleOptions, self::SingleOption => true,
            default => false,
        };
    }

    public function hasOptions(): bool
    {
        return match ($this) {
            self::MultipleOptions, self::SingleOption => true,
            default => false,
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            default => __('panel/forms.visitor.field_type.' . $this->value),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Text, self::Paragraph => 'gray',
            self::Email, self::Phone => 'info',
            self::Switch, self::Checkbox => 'success',
            self::MultipleOptions, self::SingleOption => 'warning',
            self::Date, self::Number => 'primary',
            self::File, self::Image, self::Pdf => 'danger',
        };
    }

    // public function getIcon(): ?string
    // {
    //     return match ($this) {
    //         self::Text => 'heroicon-m-text-cursor',
    //         self::Paragraph => 'heroicon-m-document-text',
    //         self::Email => 'heroicon-m-envelope',
    //         self::Phone => 'heroicon-m-phone',
    //         self::Switch => 'heroicon-m-arrow-path',
    //         self::Checkbox => 'heroicon-m-check-square',
    //         self::MultipleOptions => 'heroicon-m-list-bullet',
    //         self::SingleOption => 'heroicon-m-check-circle',
    //         self::Date => 'heroicon-m-calendar',
    //         self::Number => 'heroicon-m-currency-dollar',
    //         self::File => 'heroicon-m-document',
    //         self::Image => 'heroicon-m-image',
    //         self::Pdf => 'heroicon-m-document-pdf',
    //     };
    // }
}
