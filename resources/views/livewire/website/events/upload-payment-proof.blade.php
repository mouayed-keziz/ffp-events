<?php

use Livewire\Volt\Component;

new class extends Component {
    public $event;
    public $submission;
    public $payments;

    public function mount($event, $submission)
    {
        $this->event = $event;
        $this->submission = $submission;
        $this->payments = $this->submission->paymentSlices;
    }
}; ?>

<div>
    <pre>
        {{ json_encode($this->payments, JSON_PRETTY_PRINT) }}
    </pre>
</div>
