<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ArticleStatus: string implements HasLabel, HasColor, HasIcon
{
    case Draft = 'draft';
    case Pending = 'pending';
    case Published = 'published';
    case Deleted = 'deleted';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => __('articles.status.draft'),
            self::Pending => __('articles.status.pending'),
            self::Published => __('articles.status.published'),
            self::Deleted => __('articles.status.deleted'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Pending => 'warning',
            self::Published => 'success',
            self::Deleted => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Draft => 'heroicon-m-pencil',
            self::Pending => 'heroicon-m-clock',
            self::Published => 'heroicon-m-check',
            self::Deleted => 'heroicon-m-trash',
        };
    }

    // public function getTextColorClasses(): string
    // {
    //     return match ($this) {
    //         self::Draft => '[&_.fi-tabs-item-label]:text-gray-500 [&_.fi-tabs-item-icon]:text-gray-500 [&_.fi-badge]:text-gray-500',
    //         self::Pending => '[&_.fi-tabs-item-label]:text-yellow-500 [&_.fi-tabs-item-icon]:text-yellow-500 [&_.fi-badge]:text-yellow-500',
    //         self::Published => '[&_.fi-tabs-item-label]:text-green-500 [&_.fi-tabs-item-icon]:text-green-500 [&_.fi-badge]:text-green-500',
    //         self::Deleted => '[&_.fi-tabs-item-label]:text-red-500 [&_.fi-tabs-item-icon]:text-red-500 [&_.fi-badge]:text-red-500',
    //     };
    // }

}
