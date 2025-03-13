<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class ExhibitorPostPaymentForm extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasTranslations;

    protected $fillable = [
        'title',
        'description',
        'event_announcement_id',
        'sections'
    ];
    public $translatable = ['title', 'description'];

    protected $casts = [
        'sections' => 'array',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->singleFile();
    }

    public function getImageAttribute()
    {
        return $this->hasMedia('images') ? $this->getFirstMediaUrl('images') : null;
    }

    public function getRecordTitleAttribute()
    {
        return __("panel/forms.exhibitors.single") . " : " . $this->title;
    }

    public function eventAnnouncement(): BelongsTo
    {
        return $this->belongsTo(EventAnnouncement::class);
    }
}
