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

    /**
     * Get validation rules for this file type
     */
    public function getValidationRules(): array
    {
        $rules = ['file'];

        return match ($this) {
            self::IMAGE => array_merge($rules, ['mimes:jpg,jpeg,png,gif,bmp,webp', 'max:10240']), // 10MB max for images
            self::PDF => array_merge($rules, ['mimes:pdf', 'max:20480']), // 20MB max for PDFs
            self::ANY => array_merge($rules, ['max:25600']), // 25MB general limit
        };
    }
}
