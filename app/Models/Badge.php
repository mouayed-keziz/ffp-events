<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Badge extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'code',
        'name',
        'email',
        'position',
        'company',
        'visitor_submission_id',
        'exhibitor_submission_id',
    ];

    /**
     * Get the visitor submission this badge belongs to.
     */
    public function visitorSubmission(): BelongsTo
    {
        return $this->belongsTo(VisitorSubmission::class);
    }

    /**
     * Get the exhibitor submission this badge belongs to.
     */
    public function exhibitorSubmission(): BelongsTo
    {
        return $this->belongsTo(ExhibitorSubmission::class);
    }

    /**
     * Register media collections for the model.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    /**
     * Get the image URL attribute.
     */
    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl('image');
    }

    /**
     * Get all check-in/out logs for this badge.
     */
    public function checkLogs()
    {
        return $this->hasMany(BadgeCheckLog::class);
    }

    /**
     * Get current attendee record if this badge is currently checked in.
     */
    public function currentAttendee()
    {
        return $this->hasOne(CurrentAttendee::class);
    }
}
