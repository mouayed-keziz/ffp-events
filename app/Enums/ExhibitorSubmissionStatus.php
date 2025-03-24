<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ExhibitorSubmissionStatus: string implements HasColor, HasLabel, HasIcon
{
    case PENDING = 'pending';
    case REJECTED = 'rejected';
    case ACCEPTED = 'accepted';
    case PARTLY_PAYED = 'partly_payed';
    case FULLY_PAYED = 'fully_payed';
    case READY = 'ready';
    case ARCHIVE = 'archive';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING =>  "warning",
            self::REJECTED =>   "danger",
            self::ACCEPTED =>   "success",
            self::PARTLY_PAYED =>  "warning",
            self::FULLY_PAYED =>  "success",
            self::READY =>  "success",
            self::ARCHIVE =>  "secondary",
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => __('panel/exhibitor_submission.statuses.pending'),
            self::REJECTED => __('panel/exhibitor_submission.statuses.rejected'),
            self::ACCEPTED => __('panel/exhibitor_submission.statuses.accepted'),
            self::PARTLY_PAYED => __('panel/exhibitor_submission.statuses.partly_payed'),
            self::FULLY_PAYED => __('panel/exhibitor_submission.statuses.fully_payed'),
            self::READY => __('panel/exhibitor_submission.statuses.ready'),
            self::ARCHIVE => __('panel/exhibitor_submission.statuses.archive'),
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PENDING => 'heroicon-o-clock',
            self::REJECTED => 'heroicon-o-x-circle',
            self::ACCEPTED => 'heroicon-o-check-circle',
            self::PARTLY_PAYED => 'heroicon-o-banknotes',
            self::FULLY_PAYED => 'heroicon-o-currency-dollar',
            self::READY => 'heroicon-o-check-badge',
            self::ARCHIVE => 'heroicon-o-archive-box',
        };
    }
}
