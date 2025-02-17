<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ExhibitorFormField: string implements HasLabel
{
    case INPUT = "input";
    case SELECT = "select";
    case CHECKBOX = "checkbox";
    case RADIO = "radio";
    case UPLOAD = "upload";
    case SELECT_PRICED = "select_priced";
    case CHECKBOX_PRICED = "checkbox_priced";
    case RADIO_PRICED = "radio_priced";
    case ECOMMERCE = "ecommerce";

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
