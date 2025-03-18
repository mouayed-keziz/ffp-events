@props([
    'currentStep' => 0,
    'totalSteps' => 0,
    'isLastStep' => false,
    'isLastExhibitorForm' => false,
    'disabled' => false,
])

<div class="flex justify-start mt-8 gap-4">
    @if ($currentStep !== 0)
        <button type="button" class="btn font-semibold btn-sm rounded-md btn-outline border-base-200 border-2"
            wire:click="previousStep">
            {{ __('forms.Previous') }}
        </button>
    @endif

    @if ($currentStep === 0 && $disabled === true)
        <button {{ $submission->update_requested_at ? 'disabled' : '' }} type="button"
            class="btn font-semibold btn-sm rounded-md btn-outline border-base-200 border-2"
            wire:click="requestFormModification">
            {{ __('Demander la modification de ce formulaire') }}
        </button>
    @endif

    @if (!$isLastStep && !$isLastExhibitorForm)
        <button type="button" class="btn font-semibold btn-sm rounded-md btn-primary" wire:click="nextStep">
            {{ __('forms.Next') }}
        </button>
    @else
        <button type="submit" class="btn font-semibold btn-sm rounded-md btn-primary" wire:loading.attr="disabled">
            <div class="flex items-center">
                <span wire:loading.remove>
                    @if ($disabled)
                        {{ __('forms.Next') }}
                    @else
                        {{ __('forms.Submit') }}
                    @endif
                </span>
                <span wire:loading wire:target="submitForm" class="flex items-center">
                    <x-heroicon-o-arrow-path class="w-4 h-4 animate-spin me-2" />
                    <span>{{ __('forms.Submitting...') }}</span>
                </span>
            </div>
        </button>
    @endif
</div>
