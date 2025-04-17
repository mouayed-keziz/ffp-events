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
    public $disabled = false;
    public array $formData = [];
    public array $postForms = [];
    public int $currentStep = 0;
    public int $totalSteps = 0;
    public bool $formSubmitted = false;
    public string $successMessage = '';
    public string $preferred_currency = 'DZD';
    public float $totalPrice = 0;

    public function mount(EventAnnouncement $event)
    {
        $this->event = $event;
        $this->initFormData();
        $this->postForms = $event->exhibitorPostPaymentForms->toArray();

        // Redirect to event details page if no forms available
        if (empty($this->formData) || $this->totalSteps === 0) {
            return redirect()->route('event_details', ['id' => $this->event->id]);
        }
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
            // Instead of showing success message, redirect to info validation
            return redirect()->route('info_validation', ['id' => $this->event->id]);
        } else {
            session()->flash('error', 'An error occurred while submitting the form. Please try again.');
        }
    }

    public function isLastExhibitorForm()
    {
        return $this->currentStep === $this->totalSteps - 1;
    }
}; ?>

<div class="container mx-auto py-8 md:px-4">
    @if (!empty($formData))
        @include('website.components.forms.multi-step-form', [
            'steps' => $formData,
            'postForms' => $postForms,
            'currentStep' => $currentStep,
            'errors' => $errors,
            'formSubmitted' => $formSubmitted,
            'successMessage' => $successMessage,
        ])
    @endif

    @if (!$formSubmitted)
        @include('website.components.forms.form', [
            'disabled' => $disabled,
            'formStep' => $currentStep, // For regular forms, formStep equals currentStep
        ])
        <!-- Floating Price Indicator Component with Currency Selector -->
        @include('website.components.forms.price-indicator', [
            'totalPrice' => $totalPrice,
            'currency' => $preferred_currency,
            'disabled' => $disabled,
        ])
    @endif
</div>
