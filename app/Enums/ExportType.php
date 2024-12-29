<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ExportType: string implements HasLabel, HasColor, HasIcon
{
    case Log = 'App\Filament\Exports\LogExporter';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Log => __('panel/exports.type.log'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Log => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Log => 'heroicon-o-document-text',
        };
    }
}
