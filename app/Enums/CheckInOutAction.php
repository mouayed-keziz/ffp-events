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
            self::CHECK_IN => __('panel/my_event.relation_managers.badge_check_logs.actions.check_in'),
            self::CHECK_OUT => __('panel/my_event.relation_managers.badge_check_logs.actions.check_out'),
        };
    }
    public function getColor(): string | array | null
    {
        return match ($this) {
            self::CHECK_IN =>  'success',
            self::CHECK_OUT =>  'danger',
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
