<?php

namespace App\Models;

use App\Enums\ExhibitorSubmissionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ExhibitorSubmission extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;


    protected $fillable = [
        'exhibitor_id',
        'event_announcement_id',
        'answers',
        'post_answers',
        'status',
        'edit_deadline',
    ];

    protected $casts = [
        'answers' => 'array',
        'post_answers' => 'array',
        'edit_deadline' => 'datetime',
        'status' => ExhibitorSubmissionStatus::class
    ];

    /**
     * Determine if the submission can be edited based on deadline and permission
     */
    public function isEditable(): bool
    {
        if ($this->edit_deadline && now()->greaterThan($this->edit_deadline)) {
            return false;
        }

        return true;
    }

    /**
     * Get the exhibitor that owns the submission.
     */
    public function exhibitor(): BelongsTo
    {
        return $this->belongsTo(Exhibitor::class);
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
    public function paymentSlices()
    {
        return $this->hasMany(ExhibitorPaymentSlice::class);
    }
}
