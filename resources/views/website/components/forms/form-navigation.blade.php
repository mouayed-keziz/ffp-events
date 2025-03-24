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
            {{ __('website/exhibit-event.previous') }}
        </button>
    @endif

    @if ($currentStep === 0 && $disabled === true)
        <button {{ $submission->update_requested_at ? 'disabled' : '' }} type="button"
            class="btn font-semibold btn-sm rounded-md btn-outline border-base-200 border-2"
            wire:click="requestFormModification">
            {{ __('website/exhibit-event.request_form_modification') }}
        </button>
    @endif

    @if (!$isLastStep && !$isLastExhibitorForm)
        <button type="button" class="btn font-semibold btn-sm rounded-md btn-primary" wire:click="nextStep">
            {{ __('website/exhibit-event.next') }}
        </button>
    @else
        <button type="submit" class="btn font-semibold btn-sm rounded-md btn-primary" wire:loading.attr="disabled">
            <div class="flex items-center">
                <span wire:loading.remove>
                    @if ($disabled)
                        {{ __('website/exhibit-event.next') }}
                    @else
                        {{ __('website/exhibit-event.submit') }}
                    @endif
                </span>
                <span wire:loading wire:target="submitForm" class="flex items-center">
                    <span>{{ __('website/exhibit-event.submitting') }}</span>
                </span>
            </div>
        </button>
    @endif
</div>
