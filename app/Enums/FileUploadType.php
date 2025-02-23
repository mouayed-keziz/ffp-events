<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum FileUploadType: string implements HasLabel
{
    case IMAGE = "image";
    case PDF = "pdf";
    case ANY = "any";

    public function getLabel(): ?string
    {
        return trans('panel/forms.file_types.' . $this->value);
    }
}
