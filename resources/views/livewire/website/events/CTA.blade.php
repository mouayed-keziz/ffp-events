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
    <div class="flex flex-col gap-3 pt-4">
        <a href="{{ route('view_exhibitor_answers', $event) }}"
            class="btn btn-sm rounded-md text-sm font-semibold btn-outline border-base-200 border-2 uppercase">
            Revoir Mes Reponses
        </a>
        {{-- <a href="#"
            class="btn btn-sm rounded-md text-sm font-semibold btn-outline border-base-200 border-2 uppercase">
            Finaliser mes rsponses
        </a> --}}
        {{-- <a href="{{ route('download_invoice', $event) }}"
            class="btn rounded-md text-sm font-semibold btn-outline border-base-200 border-2 uppercase">
            telecharger le bon de commande
        </a> --}}
        {{-- <a href="#" class="btn rounded-md text-sm font-semibold btn-primary uppercase">
            creer et telechargers les badges
        </a> --}}
        <a href="{{ route('visit_event', $event) }}"
            class="btn btn-sm rounded-md text-sm font-semibold btn-outline border-base-200 border-2 normal-case">
            {{ __('website/event.visit') }}
        </a>
        <a href="{{ route('exhibit_event', $event) }}"
            class="btn btn-sm rounded-md text-sm font-semibold btn-primary normal-case">
            {{ __('website/event.exhibit_and_sponsor') }}
        </a>
        <a href="{{ route('upload_payment_proof', $event) }}"
            class="btn rounded-md text-sm font-semibold btn-primary uppercase">
            Televerser la preuve de commande
        </a>
    </div>
</div>
