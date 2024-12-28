<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum LogName: string implements HasLabel, HasColor
{
    case Categories = 'categories';
    case Articles = 'articles';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Categories => __('panel/logs.names.categories'),
            self::Articles => __('panel/logs.names.articles'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Categories => "info",
            self::Articles => "primary",
        };
    }
}
