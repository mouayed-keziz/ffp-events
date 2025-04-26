<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ExportType: string implements HasLabel, HasColor, HasIcon
{
    case Log = 'App\Filament\Exports\LogExporter';
    case Export = 'App\Filament\Exports\ExportExporter';
    case Article = 'App\Filament\Exports\ArticleExporter';
    case EventAnnouncement = 'App\Filament\Exports\EventAnnouncementExporter';
    case User = 'App\Filament\Exports\UserExporter';
    case Category = 'App\Filament\Exports\CategoryExporter';
    case Exhibitor = 'App\Filament\Exports\ExhibitorExporter';
    case Product = 'App\Filament\Exports\ProductExporter';
    case Visitor = 'App\Filament\Exports\VisitorExporter';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Log => __('panel/exports.type.log'),
            self::Export => __('panel/exports.type.export'),
            self::Article => __('panel/exports.type.article'),
            self::EventAnnouncement => __('panel/exports.type.event_announcement'),
            self::User => __('panel/exports.type.user'),
            self::Category => __('panel/exports.type.category'),
            self::Exhibitor => __('panel/exports.type.exhibitor'),
            self::Product => __('panel/exports.type.product'),
            self::Visitor => __('panel/exports.type.visitor'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Log => 'success',
            self::Export => 'success',
            self::Article => 'primary',
            self::EventAnnouncement => 'primary',
            self::User => 'danger',
            self::Category => 'primary',
            self::Exhibitor => 'danger',
            self::Product => 'primary',
            self::Visitor => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Log => 'heroicon-o-document-text',
            self::Export => 'heroicon-o-document',
            self::Article => 'heroicon-o-document',
            self::EventAnnouncement => 'heroicon-o-calendar',
            self::User => 'heroicon-o-user',
            self::Category => 'heroicon-o-tag',
            self::Exhibitor => 'heroicon-o-building-storefront',
            self::Product => 'heroicon-o-shopping-bag',
            self::Visitor => 'heroicon-o-user-group',
        };
    }
}
