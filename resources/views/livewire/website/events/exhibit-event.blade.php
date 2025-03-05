<?php

use Livewire\Volt\Component;
use App\Models\EventAnnouncement;
use App\Actions\ExhibitorFormActions;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    // Event and form data
    public EventAnnouncement $event;
    public array $formData = [];
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

    public function nextStep()
    {
        // Validate the current step before proceeding
        $this->validateCurrentStep();

        if ($this->currentStep < $this->totalSteps - 1) {
            $this->currentStep++;
        }

        // Calculate total price
        $this->calculateTotalPrice();
    }

    public function previousStep()
    {
        if ($this->currentStep > 0) {
            $this->currentStep--;
        }

        // Recalculate price when navigating back
        $this->calculateTotalPrice();
    }

    protected function validateCurrentStep()
    {
        $actions = new ExhibitorFormActions();
        $rules = $actions->getValidationRules($this->event, $this->currentStep);
        $this->validate($rules);
    }

    protected function calculateTotalPrice()
    {
        $actions = new ExhibitorFormActions();
        $this->totalPrice = $actions->calculateTotalPrice($this->formData, $this->preferred_currency);
    }

    public function submitForm()
    {
        // Validate the final step
        $this->validateCurrentStep();

        $actions = new ExhibitorFormActions();

        // Calculate final price
        $this->calculateTotalPrice();

        $formData = $this->formData;
        $formData['total_prices'] = [
            'DZD' => $actions->calculateTotalPrice($this->formData, 'DZD'),
            'EUR' => $actions->calculateTotalPrice($this->formData, 'EUR'),
            'USD' => $actions->calculateTotalPrice($this->formData, 'USD'),
        ];
        
        // Dump and die to display the form data
        dd($formData);
        // $success = $actions->saveFormSubmission($this->event, $this->formData);

        if ($success) {
            $this->formSubmitted = true;
            $this->successMessage = __('Form submitted successfully!');
        } else {
            session()->flash('error', 'An error occurred while submitting the form. Please try again.');
        }
    }
}; ?>

<div class="container mx-auto py-8 px-4">
    @include('website.components.forms.multi-step-form', [
        'steps' => $formData, 
        'currentStep' => $currentStep, 
        'errors' => $errors, 
        'formSubmitted' => $formSubmitted, 
        'successMessage' => $successMessage
    ])
    
    @if (!$formSubmitted)
        <form wire:submit.prevent="submitForm">
            @if (!empty($formData))
                <div>
                    <!-- Current form sections and fields -->
                    @foreach ($formData[$currentStep]['sections'] as $sectionIndex => $section)
                        <div class="mb-8">
                            @include('website.components.forms.input.section_title', [
                                'title' => $section['title'][app()->getLocale()] ?? ($section['title']['fr'] ?? ''),
                            ])

                            @foreach ($section['fields'] as $fieldIndex => $field)
                                @php
                                    $answerPath = "{$currentStep}.sections.{$sectionIndex}.fields.{$fieldIndex}.answer";
                                @endphp

                                @include('website.components.forms.fields', [
                                    'fields' => [$field],
                                    'answerPath' => $answerPath
                                ])

                                @error("formData.{$answerPath}")
                                    <div class="text-error text-sm mt-1">{{ $message }}</div>
                                @enderror
                            @endforeach
                        </div>
                    @endforeach

                    <!-- Form Navigation Component -->
                    @include('website.components.forms.form-navigation', [ 
                        "currentStep" => $currentStep ,
                        "totalSteps" => $totalSteps ,
                        "isLastStep" => $currentStep === $totalSteps - 1 
                    ])
                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <p class="text-center text-gray-500">{{ __('No forms available for this event.') }}</p>
                </div>
            @endif
        </form>

        <!-- Floating Price Indicator Component -->
        @include("website.components.forms.price-indicator", [ 
            "totalPrice" => $totalPrice,
            "currency" => $preferred_currency,
        ])
    @endif
</div>
