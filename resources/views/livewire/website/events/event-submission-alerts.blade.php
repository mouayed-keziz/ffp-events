<?php

use Livewire\Volt\Component;
use App\Enums\ExhibitorSubmissionStatus;

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
        <div class="alert bg-primary/20 text-primary text-sm">
            @include('website.svg.event.warning')
            <span>Vos données sont en cours de traitement par notre équipe, vous allez recevoir une notification par
                mail une fois cela est fait</span>
        </div>
    @elseif ($submission->status === ExhibitorSubmissionStatus::ACCEPTED)
        <div class="alert bg-primary/20 text-primary text-sm">
            @include('website.svg.event.warning')
            <span>Vos données ont était validées par notre équipe, veuillez maintenant téléverser votre preuve de
                paiement via la le boutton en bas.</span>
        </div>
    @elseif ($submission->status === ExhibitorSubmissionStatus::REJECTED)
        <div class="alert bg-error/30    text-error text-sm">
            @include('website.svg.event.error')
            <span>Votre inscription a était refusé par notre équipe</span>
        </div>
    @endif
</div>
