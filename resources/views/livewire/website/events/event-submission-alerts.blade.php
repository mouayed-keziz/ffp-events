<?php

use Livewire\Volt\Component;
use App\Enums\ExhibitorSubmissionStatus;
use App\Enums\PaymentSliceStatus;

new class extends Component {
    public $event;
    public $submission;

    public function mount($event, $submission)
    {
        $this->event = $event;
        $this->submission = $submission;
    }
}; ?>

<div class="my-4">
    @if ($submission instanceof \App\Models\VisitorSubmission)
        <div class="alert bg-success text-white text-xs mt-2">
            <x-heroicon-s-check-circle class="w-7 h-7" />
            <span>{{ __('website/event.visitor_registered', ['event' => $event->title]) }}</span>
        </div>
    @elseif ($submission->status === ExhibitorSubmissionStatus::PENDING)
        <div class="alert bg-primary/20 text-primary text-xs">
            @include('website.svg.event.warning')
            <span>{{ __('website/event.submission_pending') }}</span>
        </div>
    @elseif ($submission->status === ExhibitorSubmissionStatus::REJECTED)
        <div class="alert bg-error/30 text-error text-xs">
            @include('website.svg.event.error')
            <span>{{ __('website/event.submission_rejected') }}</span>
        </div>
    @elseif (
        $submission->status === ExhibitorSubmissionStatus::ACCEPTED ||
            $submission->status === ExhibitorSubmissionStatus::PARTLY_PAYED)
        @php
            $slices = $submission->paymentSlices()->orderBy('sort')->get();
            $hasProofAttached = $slices->where('status', PaymentSliceStatus::PROOF_ATTACHED)->count() > 0;
            $firstSlice = $slices->first();
        @endphp

        @if ($hasProofAttached)
            <div class="alert bg-primary/20 text-primary text-xs mt-2">
                @include('website.svg.event.warning')
                <span>{{ __('website/event.proof_processing') }}</span>
            </div>
        @elseif ($submission->status === ExhibitorSubmissionStatus::ACCEPTED)
            <div class="alert bg-primary/20 text-primary text-xs mt-2">
                @include('website.svg.event.warning')
                <span>{{ __('website/event.submission_accepted') }}</span>
            </div>
        @else
            @foreach ($slices as $index => $paymentSlice)
                @if ($paymentSlice->status === PaymentSliceStatus::VALID)
                    <div class="alert bg-success text-white text-xs mt-2">
                        <x-heroicon-s-check-circle class="w-7 h-7" />
                        <span>{{ __('website/event.payment_validated', ['price' => $paymentSlice->price, 'currency' => $paymentSlice->currency, 'event' => $event->title]) }}</span>
                    </div>
                @elseif($paymentSlice->status === PaymentSliceStatus::NOT_PAYED)
                    <div class="alert bg-primary/20 text-primary text-xs mt-2">
                        @include('website.svg.event.warning')
                        <span>{{ __('website/event.payment_pending', ['price' => $paymentSlice->price, 'currency' => $paymentSlice->currency, 'due_date' => $paymentSlice->due_to->format('d-m-Y')]) }}
                            @if ($submission->showPaymentButton)
                                {{ __('website/event.upload_payment_proof') }}
                            @endif
                        </span>
                    </div>
                    @break
                @endif
            @endforeach

            @if ($submission->canFillPostForms)
                <div class="alert bg-info/20 text-info text-xs mt-2">
                    <x-heroicon-s-information-circle class="w-7 h-7" />
                    <span>{{ __('website/event.finalize_responses') }}</span>
                </div>
            @endif
        @endif
    @elseif ($submission->status === ExhibitorSubmissionStatus::FULLY_PAYED)
        <div class="alert bg-success text-white text-xs mt-2">
            <x-heroicon-s-check-circle class="w-7 h-7" />
            <span>{{ __('website/event.fully_paid', ['event' => $event->title]) }}</span>
        </div>
    @elseif ($submission->status === ExhibitorSubmissionStatus::READY)
        <div class="alert bg-success text-white text-xs mt-2">
            <x-heroicon-s-check-circle class="w-7 h-7" />
            <span>{{ __('website/event.ready', ['event' => $event->title]) }}</span>
        </div>
    @elseif ($submission->status === ExhibitorSubmissionStatus::ARCHIVE)
        <div class="alert bg-gray-200 text-gray-700 text-xs mt-2">
            <x-heroicon-s-archive-box class="w-7 h-7" />
            <span>{{ __('website/event.archived', ['event' => $event->title]) }}</span>
        </div>
    @endif
</div>
