<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PaymentSliceStatus: string implements HasColor, HasIcon, HasLabel
{
    case NOT_PAYED = 'not_payed';
    case PROOF_ATTACHED = 'proof_attached';
    case VALID = 'valid';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::NOT_PAYED => 'Not Payed',
            self::PROOF_ATTACHED => 'Proof Attached',
            self::VALID => 'Valid',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::NOT_PAYED => 'danger',
            self::PROOF_ATTACHED => 'warning',
            self::VALID => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::NOT_PAYED => 'heroicon-o-x-circle',
            self::PROOF_ATTACHED => 'heroicon-o-document',
            self::VALID => 'heroicon-o-check-circle',
        };
    }
}
