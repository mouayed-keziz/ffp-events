<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum FormInputType: string implements HasLabel
{
    case TEXT = 'text';
    case NUMBER = 'number';
    case EMAIL = 'email';
    case PHONE = 'phone';
    case DATE = 'date';
    case PARAGRAPH = 'paragraph';

    public function getLabel(): ?string
    {
        return trans('panel/forms.input_types.' . $this->value);
    }
}
