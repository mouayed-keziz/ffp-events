<?php

namespace App\Models;

use App\Enums\ExhibitorSubmissionStatus;
use App\Enums\PaymentSliceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ExhibitorSubmission extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;


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

    public function getShowPaymentButtonAttribute(): bool
    {
        if (!in_array($this->status, [ExhibitorSubmissionStatus::ACCEPTED, ExhibitorSubmissionStatus::PARTLY_PAYED])) {
            return false;
        }

        $slices = $this->paymentSlices()->orderBy('sort')->get();

        if ($slices->isEmpty()) {
            return false;
        }

        if ($slices->where('status', PaymentSliceStatus::PROOF_ATTACHED)->count() > 0) {
            return false;
        }

        return $slices->where('status', PaymentSliceStatus::NOT_PAYED)->count() > 0;
    }


    public function getCanFillPostFormsAttribute(): bool
    {
        return $this->paymentSlices()->where('status', PaymentSliceStatus::VALID)->count() > 0 &&
            $this->status !== ExhibitorSubmissionStatus::ARCHIVE &&
            $this->status !== ExhibitorSubmissionStatus::READY &&
            $this->status !== ExhibitorSubmissionStatus::REJECTED &&
            $this->post_answers === NULL;
    }
    public function getCanDownloadInvoiceAttribute()
    {

        return $this->status === ExhibitorSubmissionStatus::ACCEPTED ||
            $this->status === ExhibitorSubmissionStatus::PARTLY_PAYED ||
            $this->status === ExhibitorSubmissionStatus::FULLY_PAYED ||
            $this->status === ExhibitorSubmissionStatus::READY ||
            $this->status === ExhibitorSubmissionStatus::ARCHIVE;
    }


    public function exhibitor(): BelongsTo
    {
        return $this->belongsTo(Exhibitor::class);
    }


    public function eventAnnouncement(): BelongsTo
    {
        return $this->belongsTo(EventAnnouncement::class);
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments');
    }
    public function paymentSlices()
    {
        return $this->hasMany(ExhibitorPaymentSlice::class)->orderBy('sort');
    }
}
