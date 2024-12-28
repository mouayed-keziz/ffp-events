<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum LogEvent: string implements HasLabel, HasColor, HasIcon
{
    case Creation = 'creation';
    case Modification = 'modification';
    case Deletion = 'deletion';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Creation => __('panel/logs.events.creation'),
            self::Modification => __('panel/logs.events.modification'),
            self::Deletion => __('panel/logs.events.deletion'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Creation => 'success',
            self::Modification => 'warning',
            self::Deletion => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Creation => 'heroicon-m-plus',
            self::Modification => 'heroicon-m-pencil-square',
            self::Deletion => 'heroicon-m-trash',
        };
    }
}
