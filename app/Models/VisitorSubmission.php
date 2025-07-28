<?php

namespace App\Models;

use App\Enums\SubmissionStatus;
use App\Traits\HasSubmissionLabelAnswers;
use App\Traits\HasVisitorSubmissionExportedAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class VisitorSubmission extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasSubmissionLabelAnswers, HasVisitorSubmissionExportedAttributes;

    protected $fillable = [
        'visitor_id',
        'event_announcement_id',
        'answers',
        'status',
        'anonymous_email', // Add field for anonymous email
    ];

    protected $casts = [
        'answers' => 'array',
        'status' => SubmissionStatus::class
    ];

    protected $appends = [
        'export_visitor_name',
        'export_visitor_email',
        'export_submission_type',
        'export_status',
        'export_has_badge',
        'export_badge_id',
        'export_badge_name',
        'export_badge_email',
        'export_badge_position',
        'export_badge_company',
        'export_attachments_count',
        'export_formatted_answers',
    ];
    public function getRecordTitleAttribute()
    {
        if ($this->visitor) {
            return __("panel/visitor_submissions.single") . " - " . $this->visitor->name;
        } else {
            return __("panel/visitor_submissions.single") . " - " . ($this->anonymous_email ?? 'Anonymous');
        }
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
        return $this->belongsTo(Visitor::class)->withTrashed()->withDefault();
        // return $this->belongsTo(Visitor::class)->withDefault();
    }

    /**
     * Helper method to get the email for this submission (either from visitor or anonymous)
     */
    public function getEmailAttribute(): ?string
    {
        return $this->visitor ? $this->visitor->email : $this->anonymous_email;
    }

    /**
     * Helper method to get the name for this submission (either from visitor or use a default)
     */
    public function getNameAttribute(): ?string
    {
        return $this->visitor ? $this->visitor->name : 'Anonymous Visitor';
    }

    /**
     * Check if this is an anonymous submission
     */
    public function isAnonymous(): bool
    {
        return $this->visitor_id === null;
    }
    public function getDisplayNameAttribute(): string
    {
        if (!$this->isAnonymous()) {
            return $this->visitor->email ? $this->visitor->email : "deleted visitor";
        } else {
            if ($this->anonymous_email) {
                return "(anonymos) " . $this->anonymous_email;
            } else {
                return 'Anonymous';
            }
        }
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
