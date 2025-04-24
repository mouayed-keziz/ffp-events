<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Role: string implements HasLabel, HasColor, HasIcon
{
    case ADMIN = 'admin';
    case SUPER_ADMIN = 'super_admin';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ADMIN => __('panel/roles.admin'),
            self::SUPER_ADMIN => __('panel/roles.super_admin'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::ADMIN => 'primary',
            self::SUPER_ADMIN => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ADMIN => 'heroicon-o-user-circle',
            self::SUPER_ADMIN => 'heroicon-o-shield-check',
        };
    }
}
