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

    case ForceDeletion = 'force_deletion';
    case Restoration = 'restoration';

    case Login = 'login';
    case Logout = 'Logout';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Creation => __('panel/logs.events.creation'),
            self::Modification => __('panel/logs.events.modification'),
            self::Deletion => __('panel/logs.events.deletion'),

            self::ForceDeletion => __('panel/logs.events.force_deletion'),
            self::Restoration => __('panel/logs.events.restoration'),

            self::Login => __('panel/logs.events.login'),
            self::Logout => __('panel/logs.events.logout'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Creation => 'success',
            self::Modification => 'warning',
            self::Deletion => 'danger',

            self::ForceDeletion => 'danger',
            self::Restoration => 'gray',

            self::Login => 'info',
            self::Logout => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Creation => 'heroicon-o-plus',
            self::Modification => 'heroicon-o-pencil-square',
            self::Deletion => 'heroicon-o-trash',

            self::ForceDeletion => 'heroicon-o-trash',
            self::Restoration => 'heroicon-o-refresh',

            self::Login => 'heroicon-o-arrow-left-start-on-rectangle',
            self::Logout => 'heroicon-o-arrow-left-end-on-rectangle',
        };
    }
}
