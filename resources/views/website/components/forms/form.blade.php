@props(['disabled' => false, 'formStep' => null])
<form wire:submit.prevent="submitForm">
    @if (!empty($formData))
        {{-- {{ dd($formData) }} --}}
        <div>
            <!-- Current form sections and fields -->
            @php
                // Use formStep if provided, otherwise fall back to currentStep
                $activeStep = $formStep !== null ? $formStep : $currentStep;
            @endphp
            @if (isset($formData[$activeStep]) && isset($formData[$activeStep]['sections']))
                @foreach ($formData[$activeStep]['sections'] as $sectionIndex => $section)
                    <div class="mb-8">
                        @include('website.components.forms.input.section_title', [
                            'title' => $section['title'][app()->getLocale()] ?? ($section['title']['fr'] ?? ''),
                        ])
                        @if (isset($formData[$activeStep]['image']) && $formData[$activeStep]['image'])
                            <div class="mb-4">
                                <img src="{{ $formData[$activeStep]['image'] }}" alt="Section Image"
                                    class="w-full h-auto rounded-lg">
                            </div>
                        @endif
                        @foreach ($section['fields'] as $fieldIndex => $field)
                            @php
                                // Remove the formData. prefix from the answerPath
                                $answerPath = "{$activeStep}.sections.{$sectionIndex}.fields.{$fieldIndex}.answer";
                                $fieldType = App\Enums\FormField::tryFrom($field['type']);
                            @endphp
                            @include('website.components.forms.fields', [
                                'fields' => [$field],
                                'answerPath' => $answerPath,
                                'disabled' => $disabled ?? false,
                            ])
                            @error("formData.{$activeStep}.sections.{$sectionIndex}.fields.{$fieldIndex}.answer")
                                <div class="text-error text-sm mt-1">{{ $message }}</div>
                            @enderror
                            <div class="h-6"></div>
                            {{-- Debug information to help troubleshoot --}}
                            @include('website.components.forms.debug-path', [
                                'answerPath' => $answerPath,
                            ])
                        @endforeach
                    </div>
                @endforeach
            @else
                <div class="rounded-lg p-4 bg-yellow-50 border border-yellow-200">
                    <p class="text-center text-yellow-700">
                        {{ __('Form data is not properly structured for this step.') }}
                    </p>
                </div>
            @endif


            @if ($currentStep === 0 && property_exists($this, 'terms_accepted'))
                <div class="mt-6 p-4 rounded-lg">
                    <div class="flex justify-start items-center gap-2">
                        <input type="checkbox" name="terms_accepted" wire:model="terms_accepted"
                            class="checkbox rounded-lg {{ $errors->has('terms_accepted') ? 'checkbox-error' : '' }}">
                        <span class="label-text font-semibold text-neutral">
                            {{ __('website/visit-event.terms_acceptance') }}
                            <a target="_blank" href="#"
                                class="link link-primary">{{ __('website/visit-event.terms_and_conditions') }}</a>
                        </span>
                    </div>
                    @error('terms_accepted')
                        <div class="text-error text-sm">{{ $message }}</div>
                    @enderror
                </div>
            @endif
            <!-- Form Navigation Component -->
            @include('website.components.forms.form-navigation', [
                'currentStep' => $currentStep,
                'totalSteps' => $totalSteps,
                'isLastStep' => $currentStep === $totalSteps - 1,
                'isLastExhibitorForm' => $this->isLastExhibitorForm(),
                'disabled' => $disabled,
            ])
        </div>
    @else
        <div class="rounded-lg">
            <p class="text:xl md:text-2xl text-center text-gray-500">
                {{ __('No forms available for this event.') }}</p>
        </div>
    @endif
</form>
