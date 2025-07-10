<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum CheckInOutAction: string implements HasLabel, HasColor, HasIcon
{
    case CHECK_IN = 'check_in';
    case CHECK_OUT = 'check_out';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CHECK_IN => 'Check In',
            self::CHECK_OUT => 'Check Out',
        };
    }
    public function getColor(): string | array | null
    {
        return match ($this) {
            self::CHECK_IN =>  'primary',
            self::CHECK_OUT =>  'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::CHECK_IN => 'heroicon-m-check-circle',
            self::CHECK_OUT => 'heroicon-m-x-circle',
        };
    }
}
