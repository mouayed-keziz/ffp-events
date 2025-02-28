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
    public const PREFERRED_CURRENCY = 'DZD';
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
        $this->totalPrice = $actions->calculateTotalPrice($this->formData, self::PREFERRED_CURRENCY);
    }

    public function submitForm()
    {
        // Validate the final step
        $this->validateCurrentStep();

        $actions = new ExhibitorFormActions();

        // Calculate final price
        $this->calculateTotalPrice();

        // Save the form submission
        $success = $actions->saveFormSubmission($this->event, $this->formData);

        if ($success) {
            $this->formSubmitted = true;
            $this->successMessage = __('Form submitted successfully!');
        } else {
            session()->flash('error', 'An error occurred while submitting the form. Please try again.');
        }
    }
    public function test() {
        dd($this->formData);
    }
}; ?>

<div class="container mx-auto py-8 px-4">
    <button wire:click="test">Test</button>
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

                    <!-- Total price display -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-lg">{{ __('Total Price') }}:</span>
                            <span class="font-bold text-xl text-primary">{{ number_format($totalPrice, 2) }}
                                {{ self::PREFERRED_CURRENCY }}</span>
                        </div>
                    </div>

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

        <pre class="hidden">{{ var_export($formData, true) }}</pre>
    @endif
</div>
