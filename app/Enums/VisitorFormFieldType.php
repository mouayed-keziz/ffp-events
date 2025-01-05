<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

// enum VisitorFormFieldType: string implements HasLabel, HasColor, HasIcon
enum VisitorFormFieldType: string implements HasLabel, HasColor
{
    case Text = 'text';
    case Paragraph = 'paragraph';
    case Email = 'email';
    case Phone = 'phone';
    case Switch = 'switch';
    case Checkbox = 'checkbox';
    case MultipleOptions = 'multiple_options';
    case SingleOption = 'single_option';
    case Date = 'date';
    case Number = 'number';
    // case File = 'file';
    // case Url = 'url';
    // case Password = 'password';

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
    //     };
    // }
}
