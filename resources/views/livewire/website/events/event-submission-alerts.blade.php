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
    @if ($submission->status === ExhibitorSubmissionStatus::PENDING)
        <div class="alert bg-primary/20 text-primary text-xs">
            @include('website.svg.event.warning')
            <span>Vos données sont en cours de traitement par notre équipe, vous allez recevoir une notification par
                mail une fois cela est fait</span>
        </div>
    @elseif ($submission->status === ExhibitorSubmissionStatus::REJECTED)
        <div class="alert bg-error/30 text-error text-xs">
            @include('website.svg.event.error')
            <span>Votre inscription a était refusé par notre équipe</span>
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
                <span>Votre preuve de paiement est en cours de traitement par notre équipe, vous allez recevoir une
                    notification par mail une fois cela est fait</span>
            </div>
        @elseif ($submission->status === ExhibitorSubmissionStatus::ACCEPTED)
            <div class="alert bg-primary/20 text-primary text-xs mt-2">
                @include('website.svg.event.warning')
                <span>Vos données ont était validées par notre équipe, veuillez maintenant téléverser votre preuve de
                    paiement via le boutton en bas.</span>
            </div>
        @else
            @foreach ($slices as $index => $paymentSlice)
                @if ($paymentSlice->status === PaymentSliceStatus::VALID)
                    <div class="alert bg-success text-white text-xs mt-2">
                        <x-heroicon-s-check-circle class="w-7 h-7" />
                        <span>Félicitations, votre inscription et paiement de
                            {{ $paymentSlice->price }}{{ $paymentSlice->currency }} comme exposant à {{ $event->title }}
                            est validé</span>
                    </div>
                @elseif($paymentSlice->status === PaymentSliceStatus::NOT_PAYED)
                    <div class="alert bg-primary/20 text-primary text-xs mt-2">
                        @include('website.svg.event.warning')
                        <span>Vous avez des paiements de {{ $paymentSlice->price }}{{ $paymentSlice->currency }} avant
                            le {{ $paymentSlice->due_to->format('d-m-Y') }} en attente pour cet événement.
                            @if ($submission->showPaymentButton)
                                Veuillez les téléverser via le bouton en bas.
                            @endif
                        </span>
                    </div>
                    @break
                @endif
            @endforeach

            @if ($submission->showFinalizeButton)
                <div class="alert bg-info/20 text-info text-xs mt-2">
                    <x-heroicon-s-information-circle class="w-7 h-7" />
                    <span>Vous pouvez maintenant finaliser vos réponses pour cet événement via le bouton "Finaliser Mes
                        Reponses".</span>
                </div>
            @endif
        @endif
    @elseif ($submission->status === ExhibitorSubmissionStatus::FULLY_PAYED)
        <div class="alert bg-success text-white text-xs mt-2">
            <x-heroicon-s-check-circle class="w-7 h-7" />
            <span>Félicitations, tous vos paiements pour {{ $event->title }} ont été validés. Votre participation en
                tant qu'exposant est confirmée.</span>
        </div>
    @elseif ($submission->status === ExhibitorSubmissionStatus::READY)
        <div class="alert bg-success text-white text-xs mt-2">
            <x-heroicon-s-check-circle class="w-7 h-7" />
            <span>Votre espace d'exposition pour {{ $event->title }} est prêt. Tout est en ordre pour
                l'événement.</span>
        </div>
    @elseif ($submission->status === ExhibitorSubmissionStatus::ARCHIVE)
        <div class="alert bg-gray-200 text-gray-700 text-xs mt-2">
            <x-heroicon-s-archive-box class="w-7 h-7" />
            <span>Cette soumission pour {{ $event->title }} a été archivée.</span>
        </div>
    @endif
</div>
