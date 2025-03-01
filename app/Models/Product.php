<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;
    use HasTranslations;

    protected $fillable = [
        'name',
        'code',
    ];

    public $translatable = ['name'];

    // Accessor to get image URL if image media exists
    public function getImageAttribute()
    {
        return $this->hasMedia('image') ? $this->getFirstMediaUrl('image') : asset("placeholder.png");
    }

    // Optionally register the media collection for the image.
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    // New accessor to return the codename (using code as codename)
    public function getCodenameAttribute()
    {
        return $this->code;
    }
}
