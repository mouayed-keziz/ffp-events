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
}; ?>

<div class="my-4">
    <div class="flex flex-col gap-3 pt-4">
        @if ($submission)
            <a href="{{ route('view_exhibitor_answers', $event) }}"
                class="btn btn-sm rounded-md text-sm font-semibold btn-outline border-base-200 border-2 uppercase">
                Revoir Mes Reponses
            </a>

            @if (
                $submission->status === ExhibitorSubmissionStatus::PENDING ||
                    $submission->status === ExhibitorSubmissionStatus::ACCEPTED)
                <a href="{{ route('download_invoice', $event) }}"
                    class="btn rounded-md text-sm font-semibold btn-outline border-base-200 border-2 uppercase">
                    Telecharger le bon de commande
                </a>
            @endif

            @if ($submission->showFinalizeButton)
                <a href="#"
                    class="btn rounded-md text-sm font-semibold btn-outline border-base-200 border-2 uppercase">
                    Finaliser Mes Reponses
                </a>
            @endif

            @if ($submission->showPaymentButton)
                <a href="{{ route('upload_payment_proof', $event) }}"
                    class="btn rounded-md text-sm font-semibold btn-primary uppercase">
                    Televerser la preuve de paiement
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
