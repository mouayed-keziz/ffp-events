<?php

use Livewire\Volt\Component;
use App\Models\EventAnnouncement;
use App\Forms\ExhibitorFormActions;
use App\Actions\Forms\FormFieldActions;
use Livewire\WithFileUploads;
use App\Traits\Livewire\HasFormSteps;
use App\Traits\Livewire\HasPrices;
use App\Traits\Livewire\HasFormFieldUpdates;

new class extends Component {
    use WithFileUploads;
    use HasFormSteps;
    use HasPrices;
    use HasFormFieldUpdates;

    public EventAnnouncement $event;
    public array $formData = [];
    public array $postFormTitles = [];
    public int $currentStep = 0;
    public int $totalSteps = 0;
    public bool $formSubmitted = false;
    public string $successMessage = '';
    public string $preferred_currency = 'EUR';
    public float $totalPrice = 0;

    public function mount(EventAnnouncement $event)
    {
        $this->event = $event;
        $this->initFormData();
    }

    public function updated($name)
    {
        if (str_starts_with($name, 'formData')) {
            $this->calculateTotalPrice();
        }
    }

    protected function initFormData()
    {
        $actions = new ExhibitorFormActions();
        $this->formData = $actions->initFormData($this->event);
        $this->totalSteps = count($this->formData);

        if ($this->totalSteps > 0) {
            $this->calculateTotalPrice();
        }
    }
    public function submitForm()
    {
        $this->validateCurrentStep();
        $actions = new ExhibitorFormActions();
        $success = $actions->saveFormSubmission($this->event, $this->formData);

        if ($success) {
            $this->formSubmitted = true;
            $this->successMessage = __('Form submitted successfully!');
        } else {
            session()->flash('error', 'An error occurred while submitting the form. Please try again.');
        }
    }
}; ?>

<div class="container mx-auto py-8 md:px-4">
    @include('website.components.forms.multi-step-form', [
        'steps' => $formData,
        'postFormTitles' => $postFormTitles,
        'currentStep' => $currentStep,
        'errors' => $errors,
        'formSubmitted' => $formSubmitted,
        'successMessage' => $successMessage,
    ])

    @if (!$formSubmitted)
        @include('website.components.forms.form')
        <!-- Floating Price Indicator Component with Currency Selector -->
        @include('website.components.forms.price-indicator', [
            'totalPrice' => $totalPrice,
            'currency' => $preferred_currency,
        ])
    @endif
</div>
