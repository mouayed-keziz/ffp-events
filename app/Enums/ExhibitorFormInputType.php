<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ExhibitorFormInputType: string implements HasLabel
{
    case Text = 'text';
    case Number = 'number';
    case Email = 'email';
    case Phone = 'phone';
    case Date = 'date';
    case Paragraph = 'paragraph';

    public function getLabel(): ?string
    {
        return trans('panel/forms.exhibitors.input_types.' . $this->value);
    }
}
