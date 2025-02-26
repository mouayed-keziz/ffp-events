<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class VisitorSubmission extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'visitor_id',
        'event_announcement_id',
        'answers',
        'status',
    ];

    protected $casts = [
        'answers' => 'array',
    ];

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
}
