<?php

namespace App\Models;

use App\Observers\EventAnnouncementObserver;
use App\Traits\Shareable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventAnnouncement extends Model implements HasMedia
{
    use HasFactory, SoftDeletes;
    use InteractsWithMedia, HasTranslations;
    use Shareable;

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
        return $this->getFirstMediaUrl('image') ? $this->getFirstMediaUrl('image') : asset("placeholder_wide.png");
    }
    public function getIsVisitorRegistrationOpenAttribute()
    {
        // If visitor registration dates are not defined, registration is closed
        if (!$this->visitor_registration_start_date || !$this->visitor_registration_end_date) {
            return false;
        }

        $now = \Carbon\Carbon::now();
        return !($now->lt($this->visitor_registration_start_date) || $now->gt($this->visitor_registration_end_date));
    }
    public function getIsExhibitorRegistrationOpenAttribute()
    {
        $now = \Carbon\Carbon::now();
        return !($now->lt($this->exhibitor_registration_start_date) || $now->gt($this->exhibitor_registration_end_date));
    }

    public function getCountdownAttribute()
    {
        $now = now();
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $diff = $start_date->diff($now);

        // Check if the event is currently ongoing (between start and end date)
        $is_ongoing = $now->greaterThanOrEqualTo($start_date) && $now->lessThanOrEqualTo($end_date);

        // If event is ongoing, return zeros for the countdown
        if ($is_ongoing) {
            return [
                'is_ongoing' => true,
                'is_past' => false,
                'diff' => [
                    'years' => 0,
                    'months' => 0,
                    'days' => 0,
                    'hours' => 0,
                    'minutes' => 0,
                    'seconds' => 0,
                ]
            ];
        }

        // If the event is past the end date
        $is_past = $now->greaterThan($end_date);

        return [
            'is_ongoing' => false,
            'is_past' => $is_past,
            'diff' => [
                'years' => $diff->y,
                'months' => $diff->m,
                'days' => $diff->d,
                'hours' => $diff->h,
                'minutes' => $diff->i,
                'seconds' => $diff->s,
            ]
        ];
    }

    /**
     * Create a duplicate of this event announcement with related forms
     * but without submissions.
     * 
     * @return EventAnnouncement
     */
    public function duplicate(): self
    {
        // Get available locales from translations
        $availableLocales = array_keys($this->getTranslations('title'));

        // Clone basic attributes
        $clone = $this->replicate();

        // Update translatable fields, adding "(cloned)" to title in each locale
        $titleTranslations = [];
        foreach ($availableLocales as $locale) {
            $titleTranslations[$locale] = $this->getTranslation('title', $locale) . ' ' . __('panel/event_announcement.cloned', [], $locale);
        }

        // Set the cloned titles
        $clone->setTranslations('title', $titleTranslations);

        // Save the clone to create a new record
        $clone->push();

        // Clone media (image)
        if ($this->hasMedia('image')) {
            $media = $this->getMedia('image')->first();
            if ($media) {
                $media->copy($clone, 'image');
            }
        }

        // Clone related forms
        // 1. Clone visitor form if exists
        if ($visitorForm = $this->visitorForm) {
            $clonedVisitorForm = $visitorForm->replicate();
            $clonedVisitorForm->event_announcement_id = $clone->id;
            $clonedVisitorForm->save();

            // Clone form fields if needed
            // (This depends on your schema, adjust as necessary)
        }

        // 2. Clone exhibitor forms if exist
        foreach ($this->exhibitorForms as $exhibitorForm) {
            $clonedExhibitorForm = $exhibitorForm->replicate();
            $clonedExhibitorForm->event_announcement_id = $clone->id;
            $clonedExhibitorForm->save();

            // Clone form fields if needed
        }

        // 3. Clone exhibitor post payment forms if exist
        foreach ($this->exhibitorPostPaymentForms as $postPaymentForm) {
            $clonedPostPaymentForm = $postPaymentForm->replicate();
            $clonedPostPaymentForm->event_announcement_id = $clone->id;
            $clonedPostPaymentForm->save();

            // Clone form fields if needed
        }

        return $clone;
    }

    // ------------------ RELATIONSHIPS ------------------

    public function visitorForm()
    {
        return $this->hasOne(VisitorForm::class);
    }

    // Define the hasMany relationship with ExhibitorForm.
    public function exhibitorForms(): HasMany
    {
        return $this->hasMany(ExhibitorForm::class);
    }
    public function exhibitorPostPaymentForms(): HasMany
    {
        return $this->hasMany(ExhibitorPostPaymentForm::class);
    }

    /**
     * Get the visitor submissions for the event announcement.
     */
    public function visitorSubmissions(): HasMany
    {
        return $this->hasMany(VisitorSubmission::class);
    }
    /**
     * Get the exhibitor submissions for the event announcement.
     */
    public function exhibitorSubmissions(): HasMany
    {
        return $this->hasMany(ExhibitorSubmission::class);
    }
}
