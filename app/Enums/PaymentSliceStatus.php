<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PaymentSliceStatus: string implements HasColor, HasLabel, HasIcon
{
    case NOT_PAYED = 'not_payed';
    case PROOF_ATTACHED = 'proof_attached';
    case VALID = 'valid';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::NOT_PAYED => 'danger',
            self::PROOF_ATTACHED => 'warning',
            self::VALID => 'success',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::NOT_PAYED => __('panel/payment_slice.statuses.not_payed'),
            self::PROOF_ATTACHED => __('panel/payment_slice.statuses.proof_attached'),
            self::VALID => __('panel/payment_slice.statuses.valid'),
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::NOT_PAYED => 'heroicon-o-exclamation-circle',
            self::PROOF_ATTACHED => 'heroicon-o-document',
            self::VALID => 'heroicon-o-check-circle',
        };
    }
}
