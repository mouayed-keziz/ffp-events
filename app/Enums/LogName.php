<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum LogName: string implements HasLabel, HasColor, HasIcon
{
    case Categories = 'categories';
    case Articles = 'articles';
    case Authentication = 'authentication';
    case EventAnnouncements = 'event_announcements';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Categories => __('panel/logs.names.categories'),
            self::Articles => __('panel/logs.names.articles'),
            self::Authentication => __('panel/logs.names.authentication'),
            self::EventAnnouncements => __('panel/logs.names.event_announcements'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Categories => "primary",
            self::Articles => "success",
            self::Authentication => "info",
            self::EventAnnouncements => "warning",
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Categories => "heroicon-o-bars-3",
            self::Articles => "heroicon-o-document-duplicate",
            self::Authentication => "heroicon-o-user",
            self::EventAnnouncements => "heroicon-o-megaphone",
        };
    }
}
