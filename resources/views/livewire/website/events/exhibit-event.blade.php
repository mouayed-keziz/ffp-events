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
        // dd($this->formData);
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
}; ?>

<div class="container mx-auto py-8 px-4">
    @if (session('error'))
        <div class="alert alert-error mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if ($formSubmitted)
        <div class="rounded-btn alert alert-success mb-4 shadow-md text-white">
            <x-heroicon-o-check-circle class="w-6 h-6 inline-block mr-2" />
            {{ $successMessage }}
            <pre class="hidden">{{ var_export($formData, true) }}</pre>
        </div>
    @else
        <!-- Multi-step progress indicator -->
        <div class="mb-8">
            <div class="hidden sm:block">
                <div class="relative flex items-center justify-between">
                    @for ($i = 0; $i < $totalSteps; $i++)
                        <!-- Step indicator -->
                        <div class="flex flex-col items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center {{ $i <= $currentStep ? 'bg-primary' : 'bg-gray-300' }}">
                                <span class="text-white font-medium">{{ $i + 1 }}</span>
                            </div>
                            <div class="mt-2 text-center">
                                <span class="{{ $i <= $currentStep ? 'font-bold text-primary' : 'text-gray-700' }}">
                                    {{-- {{ isset($formData[$i]['title']) ? $formData[$i]['title'][app()->getLocale()] ?? $formData[$i]['title'] : '' }} --}}
                                </span>
                            </div>
                        </div>

                        <!-- Connector line between steps (except after the last step) -->
                        @if ($i < $totalSteps - 1)
                            <div class="flex-1 mx-2">
                                <div class="h-1 rounded-btn {{ $i < $currentStep ? 'bg-primary' : 'bg-gray-300' }}"></div>
                            </div>
                        @endif
                    @endfor
                </div>
            </div>

            <!-- Mobile progress indicator (simplified) -->
            <div class="sm:hidden">
                <div class="relative flex items-center justify-between">
                    @for ($i = 0; $i < $totalSteps; $i++)
                        <!-- Step circle -->
                        <div
                            class="w-6 h-6 rounded-full flex items-center justify-center {{ $i <= $currentStep ? 'bg-primary' : 'bg-gray-300' }}">
                        </div>

                        <!-- Connector line between steps (except after the last step) -->
                        @if ($i < $totalSteps - 1)
                            <div class="flex-1">
                                <div class="h-1 {{ $i < $currentStep ? 'bg-primary' : 'bg-gray-300' }}"></div>
                            </div>
                        @endif
                    @endfor
                </div>
            </div>
        </div>

        <!-- Form content -->
        <form wire:submit.prevent="submitForm">
            @if (!empty($formData))
                <div>
                    <!-- Current form step title and description -->
                    <div class="mb-6">
                        <h2 class="text-xl font-bold">
                            {{ isset($formData[$currentStep]['title']) ? $formData[$currentStep]['title'][app()->getLocale()] ?? '' : '' }}
                        </h2>
                        @if (isset($formData[$currentStep]['description']) && !empty($formData[$currentStep]['description'][app()->getLocale()]))
                            <p class="text-gray-600 mt-2">
                                {{ $formData[$currentStep]['description'][app()->getLocale()] }}
                            </p>
                        @endif
                    </div>

                    <!-- Current form sections and fields -->
                    @foreach ($formData[$currentStep]['sections'] as $sectionIndex => $section)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4">
                                {{ $section['title'][app()->getLocale()] ?? ($section['title']['en'] ?? '') }}
                            </h3>

                            @foreach ($section['fields'] as $fieldIndex => $field)
                                @php
                                    $answerPath = "{$currentStep}.sections.{$sectionIndex}.fields.{$fieldIndex}.answer";
                                    $quantityPath = "{$currentStep}.sections.{$sectionIndex}.fields.{$fieldIndex}.quantity";
                                @endphp

                                <div class="mb-6">
                                    <div class="form-control w-full">
                                       

                                        <!-- Input field based on type -->
                                        @switch($field['type'])
                                            @case(App\Enums\FormField::INPUT->value)
                                                @include('website.components.forms.input.input', [
                                                    'data' => $field['data'],
                                                    'answerPath' => $answerPath ?? null,
                                                ])
                                            @break

                                            @case(App\Enums\FormField::SELECT->value)
                                                @include('website.components.forms.multiple.select', [
                                                    'data' => $field['data'],
                                                    'answerPath' => $answerPath ?? null,
                                                ])
                                            @break

                                            @case(App\Enums\FormField::CHECKBOX->value)
                                                @include('website.components.forms.multiple.checkbox', [
                                                    'data' => $field['data'],
                                                    'answerPath' => $answerPath ?? null,
                                                ])
                                            @break

                                            @case(App\Enums\FormField::RADIO->value)
                                                @include('website.components.forms.multiple.radio', [
                                                    'data' => $field['data'],
                                                    'answerPath' => $answerPath ?? null,
                                                ])
                                            @break

                                            @case(App\Enums\FormField::UPLOAD->value)
                                                @include('website.components.forms.file-upload', [
                                                    'data' => $field['data'],
                                                    'answerPath' => $answerPath ?? null,
                                                ])
                                            @break

                                            @default
                                                <div>_</div>
                                        @endswitch

                                        @error("formData.{$answerPath}")
                                            <div class="text-error text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
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
    @endif
</div>
