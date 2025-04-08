<?php

namespace App\Models;

use App\Observers\PlanObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Plan extends Model implements HasMedia
{
    use HasTranslations;
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'content',
        'price',
        'plan_tier_id',
    ];

    public $translatable = ['title', 'content'];

    protected $casts = [
        'price' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(PlanObserver::class);
    }

    public function planTier(): BelongsTo
    {
        return $this->belongsTo(PlanTier::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();
    }

    public function getImageAttribute()
    {
        return $this->hasMedia('image') ? $this->getFirstMediaUrl('image') : asset("placeholder.png");
    }
}
