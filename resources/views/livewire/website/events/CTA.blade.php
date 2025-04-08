<?php

use Livewire\Volt\Component;
use App\Enums\ExhibitorSubmissionStatus;
use App\Enums\PaymentSliceStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

new class extends Component {
    public $event;
    public $submission;
    public $locale;

    public function mount($event, $submission)
    {
        $this->event = $event;
        $this->submission = $submission;
        $this->locale = App::getLocale();
    }

    public function shouldShowVisitButton()
    {
        if ($this->submission) {
            return false;
        }

        if (empty($this->event->visitorForm->sections)) {
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

        if (empty($this->event->exhibitorForms->toArray())) {
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
            @if ($submission->status === ExhibitorSubmissionStatus::REJECTED)
                <div class="alert alert-error shadow-lg">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h3 class="font-bold">{{ __('website/event.submission_rejected') }}</h3>
                            <div class="text-sm mt-2">{{ $submission->rejection_reason }}</div>
                        </div>
                    </div>
                </div>
            @endif

            @if (!Auth::guard('visitor')->check())
                <a href="{{ route('view_exhibitor_answers', $event) }}"
                    class="btn btn-sm rounded-md text-sm font-semibold btn-outline border-base-200 border-2 uppercase">
                    {{ __('website/event.review_answers') }}
                </a>
            @endif

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
                <a href="https://ffp-events.com/service-amenagement" target="_blank"
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
