<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SubmissionStatus: string implements HasColor, HasLabel
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => __('exhibitor_submission.status.pending'),
            self::APPROVED => __('exhibitor_submission.status.approved'),
            self::REJECTED => __('exhibitor_submission.status.rejected'),
        };
    }
}
