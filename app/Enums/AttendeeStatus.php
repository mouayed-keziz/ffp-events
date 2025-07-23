<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum AttendeeStatus: string implements HasLabel, HasColor, HasIcon
{
    case INSIDE = 'inside';
    case OUTSIDE = 'outside';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::INSIDE => __("panel/scanner.attende_status.inside"),
            self::OUTSIDE => __("panel/scanner.attende_status.outside"),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::INSIDE => 'success',
            self::OUTSIDE => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::INSIDE => 'heroicon-m-arrow-right-on-rectangle',
            self::OUTSIDE => 'heroicon-m-arrow-left-on-rectangle',
        };
    }
}
