<?php

namespace App\Models;

use App\Observers\EventAnnouncementObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class EventAnnouncement extends Model implements HasMedia
{
    use HasFactory, SoftDeletes;
    use InteractsWithMedia, HasTranslations;

    protected $fillable = [
        'title',
        'description',
        'terms',
        'content',
        'location',
        'start_date',
        'end_date',
        'visitor_registration_start_date',
        'visitor_registration_end_date',
        'exhibitor_registration_start_date',
        'exhibitor_registration_end_date',
        'website_url',
        'contact',
        'currencies',
    ];
    public $translatable = ['title', 'description', 'terms', 'content'];

    protected $casts = [
        'start_date'                      => 'datetime',
        'end_date'                        => 'datetime',
        'visitor_registration_start_date' => 'datetime',
        'visitor_registration_end_date'   => 'datetime',
        'exhibitor_registration_start_date' => 'datetime',
        'exhibitor_registration_end_date'   => 'datetime',
        'website_url'                     => 'string',
        'contact'                         => 'array',
        'currencies'                      => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(EventAnnouncementObserver::class);
    }

    public function getRecordTitleAttribute()
    {
        return __("panel/event_announcement.resource.label") . " - " . $this->title;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();
    }
    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl('image') ? $this->getFirstMediaUrl('image') : null;
    }



    // ------------------ RELATIONSHIPS ------------------

    public function visitorForm()
    {
        return $this->hasOne(VisitorForm::class);
    }
}
