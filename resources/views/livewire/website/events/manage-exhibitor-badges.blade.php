<?php

use Livewire\Volt\Component;
use App\Models\EventAnnouncement;
use App\Models\ExhibitorSubmission;

new class extends Component {
    public $event;
    public $submission;

    public function mount(EventAnnouncement $event, ExhibitorSubmission $submission)
    {
        $this->event = $event;
        $this->submission = $submission;
    }
}; ?>

<div>
    {{ $submission->id }}
    <div class="w-full h-12 flex justify-between items-center gap-4">
        <div class="w-12 h-12 bg-primary rounded-md"></div>
        <div class="w-12 h-12 bg-base-100 rounded-md"></div>
        <div class="w-12 h-12 bg-base-200 rounded-md"></div>
        <div class="w-12 h-12 bg-base-300 rounded-md"></div>
    </div>
</div>
