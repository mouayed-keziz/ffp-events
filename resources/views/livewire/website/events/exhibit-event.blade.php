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
    public string $preferred_currency = 'EUR';
    public float $totalPrice = 0;

    public function mount(EventAnnouncement $event)
    {
        $this->event = $event;
        $this->initFormData();
    }

   

    protected function initFormData()
    {
        $actions = new ExhibitorFormActions();
        $this->formData = $actions->initFormData($this->event);
        $this->totalSteps = count($this->formData);
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

        // Save the form submission
        dd($this->formData);
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

                    <!-- Navigation buttons -->
                    <div class="flex justify-between mt-8">
                        <button type="button" class="btn btn-outline" wire:click="previousStep"
                            {{ $currentStep === 0 ? 'disabled' : '' }}>
                            <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                            {{ __('Previous') }}
                        </button>

                        @if ($currentStep < $totalSteps - 1)
                            <button type="button" class="btn btn-primary" wire:click="nextStep">
                                {{ __('Next') }}
                                <x-heroicon-o-arrow-right class="w-5 h-5 ml-2" />
                            </button>
                        @else
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>{{ __('Submit') }}</span>
                                <span wire:loading wire:target="submitForm">
                                    <x-heroicon-o-arrow-path class="w-5 h-5 animate-spin mr-2" />
                                    {{ __('Submitting...') }}
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <p class="text-center text-gray-500">{{ __('No forms available for this event.') }}</p>
                </div>
            @endif
        </form>

        <!-- Floating Total Price -->
        <div class="fixed bottom-4 right-4 z-10 w-full sm:w-80 md:w-96">
            <div class="bg-white shadow-lg rounded-btn p-4 border">
                <h3 class="font-semibold mb-4">{{ __('Totale du bon de commande') }}</h3>
                <div class="mb-2 bg-primary/10 py-2 px-4 rounded-btn">
                    <div class="text-primary text-xs font-semibold">{{ __('Total HT') }}</div>
                    <div class="font-bold text-md text-primary">
                        {{ number_format($totalPrice, 2) }} {{ $this->preferred_currency }}
                    </div>
                </div>
                <div class="bg-primary/10 py-2 px-4 rounded-btn">
                    <div class="text-primary text-xs font-semibold">{{ __('Total HT') }}</div>
                    <div class="font-bold text-md text-primary">
                        {{ number_format($totalPrice, 2) }} {{ $this->preferred_currency }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
