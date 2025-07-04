<?php

namespace App\Models;

use App\Enums\ExhibitorSubmissionStatus;
use App\Enums\PaymentSliceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use App\Enums\FormField;

class ExhibitorSubmission extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTranslations;

    public $translatable = ['rejection_reason'];

    protected $fillable = [
        'exhibitor_id',
        'event_announcement_id',
        'answers',
        'post_answers',
        'total_prices',
        'status',
        'rejection_reason',
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
    public function getRecordTitleAttribute()
    {
        if ($this->exhibitor && $this->exhibitor->name) {
            return __("panel/exhibitor_submission.resource.label") . " - " . $this->exhibitor->name;
        }
        return __("panel/exhibitor_submission.resource.label") . " - " . $this->id;
    }
    public function getRecordLinkAttribute()
    {
        return route('filament.admin.resources.exhibitor-submissions.view', ['record' => $this->id]);
    }
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
            $this->post_answers === NULL &&
            $this->eventAnnouncement->exhibitorPostPaymentForms->count() > 0;
    }
    public function getCanDownloadInvoiceAttribute()
    {
        $invoiceData = $this->getInvoiceData();
        return !empty($invoiceData) &&
            (
                $this->status === ExhibitorSubmissionStatus::ACCEPTED ||
                $this->status === ExhibitorSubmissionStatus::PARTLY_PAYED ||
                $this->status === ExhibitorSubmissionStatus::FULLY_PAYED ||
                $this->status === ExhibitorSubmissionStatus::READY ||
                $this->status === ExhibitorSubmissionStatus::ARCHIVE
            );
    }
    public function getCanAdminDownloadInvoiceAttribute()
    {
        $invoiceData = $this->getInvoiceData();
        return !empty($invoiceData);
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


    public function getInvoiceData(): array
    {
        $invoiceData = [];

        foreach ($this->answers as $answer) {
            if (isset($answer['sections'])) {
                foreach ($answer['sections'] as $section) {
                    foreach ($section['fields'] as $field) {
                        $fieldType = FormField::tryFrom($field['type']);
                        if ($fieldType && $fieldType->isPriced()) {
                            $invoiceData = array_merge($invoiceData, $fieldType->getInvoiceDetails($field));
                        }
                    }
                }
            }
        }

        return $invoiceData;
    }

    /**
     * Get the badges associated with the exhibitor submission.
     */
    public function badges()
    {
        return $this->hasMany(Badge::class);
    }

    /**
     * Calculate and update the submission status based on payment slices
     */
    public function updateStatusBasedOnPaymentSlices(): void
    {
        $paymentSlices = $this->paymentSlices;

        // If no payment slices exist, keep status as ACCEPTED
        if ($paymentSlices->isEmpty()) {
            $this->status = ExhibitorSubmissionStatus::ACCEPTED;
            $this->save();
            return;
        }

        $validSlicesCount = $paymentSlices->where('status', PaymentSliceStatus::VALID)->count();
        $totalSlicesCount = $paymentSlices->count();

        if ($validSlicesCount === 0) {
            // No valid payments
            $this->status = ExhibitorSubmissionStatus::ACCEPTED;
        } elseif ($validSlicesCount === $totalSlicesCount) {
            // All payments are valid
            $this->status = ExhibitorSubmissionStatus::FULLY_PAYED;
        } else {
            // Some payments are valid but not all
            $this->status = ExhibitorSubmissionStatus::PARTLY_PAYED;
        }

        $this->save();
    }
}
