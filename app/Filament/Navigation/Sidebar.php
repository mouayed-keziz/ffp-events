<?php

namespace App\Filament\Navigation;

class Sidebar
{
    const EVENT_ANNOUNCEMENT = [
        'icon' => 'heroicon-o-megaphone',
        'sort' => 1
    ];
    const ARTICLE = [
        'icon' => 'heroicon-o-document-duplicate',
        'sort' => 2
    ];
    const CATEGORY = [
        'icon' => 'heroicon-o-bars-3',
        'sort' => 3
    ];
    const EXHIBITOR = [
        'icon' => 'heroicon-o-users',
        'sort' => 4
    ];
    const VISITOR = [
        'icon' => 'heroicon-o-users',
        'sort' => 5
    ];
    const ADMIN = [
        'icon' => 'heroicon-o-users',
        'sort' => 6
    ];
    const COMPANY_INFORMATION = [
        'icon' => 'heroicon-o-building-office',
        'sort' => 7
    ];
    const LOG = [
        'icon' => 'heroicon-o-clipboard-document-list',
        'sort' => 8
    ];
    const EXPORT = [
        'icon' => 'heroicon-o-arrow-down-tray',
        'sort' => 9
    ];

    public static function icon(string $resource): string
    {
        return constant("self::$resource")['icon'];
    }

    public static function sort(string $resource): int
    {
        return constant("self::$resource")['sort'];
    }
}
