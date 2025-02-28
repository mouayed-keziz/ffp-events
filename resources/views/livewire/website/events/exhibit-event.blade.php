<?php

use Livewire\Volt\Component;
use App\Models\EventAnnouncement;

new class extends Component {
    public int $step = 1;
    public EventAnnouncement $event;
    public array $formData = [];
    public bool $formSubmitted = false;
    public string $successMessage = '';

    public function mount(EventAnnouncement $event)
    {
        $this->event = $event;
        // $this->initFormData();
    }
}; ?>

<div>
    // {{$event->title}}
</div>
