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
                            // Remove the formData. prefix from the answerPath
                            $answerPath = "{$currentStep}.sections.{$sectionIndex}.fields.{$fieldIndex}.answer";
                            $fieldType = App\Enums\FormField::tryFrom($field['type']);
                        @endphp

                        @include('website.components.forms.fields', [
                            'fields' => [$field],
                            'answerPath' => $answerPath,
                        ])

                        @error("formData.{$currentStep}.sections.{$sectionIndex}.fields.{$fieldIndex}.answer")
                            <div class="text-error text-sm mt-1">{{ $message }}</div>
                        @enderror

                        {{-- Debug information to help troubleshoot --}}
                        @include('website.components.forms.debug-path', [
                            'answerPath' => $answerPath,
                        ])
                    @endforeach
                </div>
            @endforeach

            <!-- Form Navigation Component -->
            @include('website.components.forms.form-navigation', [
                'currentStep' => $currentStep,
                'totalSteps' => $totalSteps,
                'isLastStep' => $currentStep === $totalSteps - 1,
                'isLastExhibitorForm' => $this->isLastExhibitorForm(),
            ])
        </div>
    @else
        <div class="rounded-lg">
            <p class="text:xl md:text-2xl text-center text-gray-500">
                {{ __('No forms available for this event.') }}</p>
        </div>
    @endif
</form>
