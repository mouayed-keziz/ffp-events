<?php

namespace App\Utils;

use Illuminate\Support\Str;

class SlugUtils
{
    public static function generateSlugFromMultilingualName(array $name): string
    {
        // Try French first
        if (isset($name['fr']) && !empty($name['fr'])) {
            return Str::slug($name['fr']);
        }
        // Then English
        if (isset($name['en']) && !empty($name['en'])) {
            return Str::slug($name['en']);
        }
        // Finally Arabic
        if (isset($name['ar']) && !empty($name['ar'])) {
            return Str::slug($name['ar']);
        }

        return '';
    }
}
