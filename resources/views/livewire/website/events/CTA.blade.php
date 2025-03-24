<?php

use Livewire\Volt\Component;
use App\Enums\ExhibitorSubmissionStatus;
use App\Enums\PaymentSliceStatus;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $event;
    public $submission;

    public function mount($event, $submission)
    {
        $this->event = $event;
        $this->submission = $submission;
    }

    public function hasUnpaidPayments()
    {
        return $this->submission->paymentSlices()->where('status', PaymentSliceStatus::NOT_PAYED)->exists();
    }

    public function isFullyPaid()
    {
        return $this->submission->paymentSlices()->where('status', '!=', PaymentSliceStatus::VALID)->count() === 0 && $this->submission->paymentSlices()->count() > 0;
    }

    public function shouldShowVisitButton()
    {
        if ($this->submission) {
            return false;
        }

        if (Auth::guard('exhibitor')->check()) {
            return false;
        }

        if (Auth::guard('visitor')->check() && $this->submission) {
            return false;
        }

        return true;
    }

    public function shouldShowExhibitButton()
    {
        if ($this->submission) {
            return false;
        }

        if (Auth::guard('visitor')->check()) {
            return false;
        }

        return true;
    }

    public function isFullyPaidOrReady()
    {
        if (!$this->submission) {
            return false;
        }

        return $this->submission->status === ExhibitorSubmissionStatus::FULLY_PAYED || $this->submission->status === ExhibitorSubmissionStatus::READY;
    }
}; ?>

<div class="my-4">
    <div class="flex flex-col gap-3 pt-4">
        @if ($submission)
            <a href="{{ route('view_exhibitor_answers', $event) }}"
                class="btn btn-sm rounded-md text-sm font-semibold btn-outline border-base-200 border-2 uppercase">
                {{ __('website/event.review_answers') }}
            </a>

            @if ($submission->canDownloadInvoice)
                <a href="{{ route('download_invoice', $event) }}"
                    class="btn rounded-md text-sm font-semibold btn-outline border-base-200 border-2 uppercase">
                    {{ __('website/event.download_invoice') }}
                </a>
            @endif

            @if ($submission->canFillPostForms)
                <a href="{{ route('post_exhibit_event', $event) }}"
                    class="btn btn-sm rounded-md text-sm font-semibold btn-outline border-base-200 border-2 uppercase">
                    {{ __('website/event.finalize_answers') }}
                </a>
            @endif

            @if ($submission->showPaymentButton)
                <a href="{{ route('upload_payment_proof', $event) }}"
                    class="btn rounded-md text-sm font-semibold btn-primary uppercase">
                    {{ __('website/event.upload_payment_proof') }}
                </a>
            @endif

            @if ($submission->status === ExhibitorSubmissionStatus::FULLY_PAYED)
                <a href="#" class="btn rounded-md text-sm font-semibold btn-primary uppercase">
                    {{ __('website/event.create_download_badges') }}
                </a>
            @endif

            @if (
                $submission->status === ExhibitorSubmissionStatus::FULLY_PAYED ||
                    $submission->status === ExhibitorSubmissionStatus::READY)
                <a href="#"
                    class="btn btn-sm rounded-md text-sm font-semibold btn-error bg-red-600 hover:bg-red-700 text-white uppercase">
                    {{ __('website/event.setup_booth') }}
                </a>
            @endif
        @endif

        @if ($this->shouldShowVisitButton())
            <a href="{{ route('visit_event', $event) }}"
                class="btn btn-sm rounded-md text-sm font-semibold btn-outline border-base-200 border-2 normal-case">
                {{ __('website/event.visit') }}
            </a>
        @endif

        @if ($this->shouldShowExhibitButton())
            <a href="{{ route('exhibit_event', $event) }}"
                class="btn btn-sm rounded-md text-sm font-semibold btn-primary normal-case">
                {{ __('website/event.exhibit_and_sponsor') }}
            </a>
        @endif
    </div>
</div>
