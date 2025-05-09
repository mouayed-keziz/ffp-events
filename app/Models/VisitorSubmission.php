<?php

namespace App\Models;

use App\Enums\SubmissionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class VisitorSubmission extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'visitor_id',
        'event_announcement_id',
        'answers',
        'status',
    ];

    protected $casts = [
        'answers' => 'array',
        'status' => SubmissionStatus::class
    ];
    public function getRecordTitleAttribute()
    {
        return __("panel/visitor_submissions.single") . " - " . $this->visitor->name;
    }
    public function getRecordLinkAttribute()
    {
        return route('filament.admin.resources.event-announcements.visitor-submission.view', ["record" => $this->eventAnnouncement->id, 'visitorSubmission' => $this->id]);
    }
    /**
     * Get the visitor that owns the submission.
     */
    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    /**
     * Get the event announcement that the submission is for.
     */
    public function eventAnnouncement(): BelongsTo
    {
        return $this->belongsTo(EventAnnouncement::class);
    }

    /**
     * Register media collections for the model.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments');
    }

    /**
     * Get the badge associated with the visitor submission.
     */
    public function badge()
    {
        return $this->hasOne(Badge::class);
    }
}
