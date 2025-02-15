<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExhibitorForm extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'event_announcement_id',
        // ...other attributes...
    ];

    // Register the media collection for images.
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->singleFile();
    }

    public function getImageAttribute()
    {
        return $this->hasMedia('images') ? $this->getFirstMediaUrl('images') : null;
    }

    // Define the belongsTo relationship with EventAnnouncement.
    public function eventAnnouncement(): BelongsTo
    {
        return $this->belongsTo(EventAnnouncement::class);
    }
}
