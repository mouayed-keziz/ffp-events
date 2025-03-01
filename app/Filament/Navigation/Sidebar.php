<?php

namespace App\Filament\Navigation;

class Sidebar
{
    const EVENT_ANNOUNCEMENT = [
        'icon' => 'heroicon-o-megaphone',
        'sort' => 1,
        'group' => 'panel/nav.groups.event_management',
    ];
    const ARTICLE = [
        'icon' => 'heroicon-o-document-duplicate',
        'sort' => 2,
        'group' => 'panel/nav.groups.articles',
    ];
    const CATEGORY = [
        'icon' => 'heroicon-o-bars-3',
        'sort' => 3,
        'group' => 'panel/nav.groups.articles',
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
        'sort' => 8,
        'group' => 'panel/nav.groups.management'
    ];
    const EXPORT = [
        'icon' => 'heroicon-o-arrow-down-tray',
        'sort' => 9,
        'group' => 'panel/nav.groups.management'
    ];
    const PRODUCT = [
        'icon' => 'heroicon-o-shopping-bag',
        'sort' => 10,
        'group' => 'panel/nav.groups.event_management'
    ];
    const PLAN_TIER = [
        'icon' => 'heroicon-o-table-cells',
        'sort' => 11,
        'group' => 'panel/nav.groups.event_management'
    ];

    const SETTINGS = [
        'icon' => 'heroicon-o-cog',
        'sort' => 12,
        'group' => 'panel/nav.groups.settings'
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
