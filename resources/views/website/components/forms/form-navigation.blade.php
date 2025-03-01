@props([
    'currentStep' => 0,
    'totalSteps' => 0,
    'isLastStep' => false
])

<div class="flex justify-between mt-8">
    <button type="button" class="btn btn-outline btn-sm" 
        wire:click="previousStep" 
        {{ $currentStep === 0 ? 'disabled' : '' }}>
        @if(app()->getLocale() === 'ar')
            {{ __('forms.Previous') }}
            <x-heroicon-o-arrow-right class="w-4 h-4 ms-2" />
        @else
            <x-heroicon-o-arrow-left class="w-4 h-4 me-2" />
            {{ __('forms.Previous') }}
        @endif
    </button>

    @if (!$isLastStep)
        <button type="button" class="btn btn-primary btn-sm" wire:click="nextStep">
            @if(app()->getLocale() === 'ar')
                <x-heroicon-o-arrow-left class="w-4 h-4 me-2" />
                {{ __('forms.Next') }}
            @else
                {{ __('forms.Next') }}
                <x-heroicon-o-arrow-right class="w-4 h-4 ms-2" />
            @endif
        </button>
    @else
        <button type="submit" class="btn btn-primary btn-sm" wire:loading.attr="disabled">
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