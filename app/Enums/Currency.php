<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Currency: string implements HasLabel
{
    case DA = 'DZD';
    case EUR = 'EUR';
    case USD = 'USD';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DA  => 'Algerian Dinar',
            self::EUR => 'Euro',
            self::USD => 'US Dollar',
        };
    }
}
