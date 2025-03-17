@props([
    'currentStep' => 0,
    'totalSteps' => 0,
    'isLastStep' => false,
    'isLastExhibitorForm' => false,
])

<div class="flex justify-start mt-8">
    <button type="button" class="btn btn-outline border-base-200 border-2" wire:click="previousStep"
        {{ $currentStep === 0 ? 'disabled' : '' }}>
        {{ __('forms.Previous') }}
    </button>

    @if (!$isLastStep && !$isLastExhibitorForm)
        <button type="button" class="btn btn-primary ms-4" wire:click="nextStep">
            {{ __('forms.Next') }}
        </button>
    @else
        <button type="submit" class="btn btn-primary ms-4" wire:loading.attr="disabled">
            <div class="flex items-center">
                <span wire:loading.remove>{{ __('forms.Submit') }}</span>
                <span wire:loading wire:target="submitForm" class="flex items-center">
                    <x-heroicon-o-arrow-path class="w-4 h-4 animate-spin me-2" />
                    <span>{{ __('forms.Submitting...') }}</span>
                </span>
            </div>
        </button>
    @endif
</div>
