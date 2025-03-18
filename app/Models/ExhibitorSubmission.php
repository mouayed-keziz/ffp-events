<?php

namespace App\Models;

use App\Enums\ExhibitorSubmissionStatus;
use App\Enums\PaymentSliceStatus;
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
        'total_prices',
        'status',
        'edit_deadline',
        'update_requested_at'
    ];

    protected $casts = [
        'answers' => 'array',
        'post_answers' => 'array',
        'total_prices' => 'array',
        'edit_deadline' => 'datetime',
        'update_requested_at' => 'datetime',
        'status' => ExhibitorSubmissionStatus::class
    ];

    /**
     * Determine if the submission can be edited based on deadline and permission
     */
    public function getIsEditableAttribute(): bool
    {
        if (!$this->edit_deadline) {
            return false;
        }
        if (now()->lessThan($this->edit_deadline)) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the payment button should be shown
     */
    public function getShowPaymentButtonAttribute(): bool
    {
        // If status is not ACCEPTED or PARTLY_PAYED, don't show the button
        if (!in_array($this->status, [ExhibitorSubmissionStatus::ACCEPTED, ExhibitorSubmissionStatus::PARTLY_PAYED])) {
            return false;
        }

        // Get payment slices ordered by sort
        $slices = $this->paymentSlices()->orderBy('sort')->get();

        // If no slices, don't show the button
        if ($slices->isEmpty()) {
            return false;
        }

        // If any slice has PROOF_ATTACHED status, don't show button until admin validates it
        if ($slices->where('status', PaymentSliceStatus::PROOF_ATTACHED)->count() > 0) {
            return false;
        }

        // Check if there's any slice that needs payment (NOT_PAYED status)
        return $slices->where('status', PaymentSliceStatus::NOT_PAYED)->count() > 0;
    }

    /**
     * Determine if the finalize button should be shown
     */
    public function getShowFinalizeButtonAttribute(): bool
    {
        // Show finalize button when at least one payment is valid
        return $this->paymentSlices()->where('status', PaymentSliceStatus::VALID)->count() > 0 &&
            $this->status !== ExhibitorSubmissionStatus::FULLY_PAYED &&
            $this->status !== ExhibitorSubmissionStatus::READY;
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
        return $this->hasMany(ExhibitorPaymentSlice::class)->orderBy('sort');
    }
}
