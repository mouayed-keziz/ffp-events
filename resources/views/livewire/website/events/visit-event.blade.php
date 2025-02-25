<?php
use Livewire\Volt\Component;
use App\Models\EventAnnouncement;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public EventAnnouncement $event;
    public array $formData = [];
    public bool $formSubmitted = false;
    public string $successMessage = '';

    public function mount(EventAnnouncement $event)
    {
        $this->event = $event;
        $this->initFormData();
    }

    protected function initFormData()
    {
        // Initialize form data structure based on form sections and fields
        if (!$this->event->visitorForm) {
            return;
        }

        $this->formData = [];

        // Create the structured formData with sections and fields
        foreach ($this->event->visitorForm->sections as $sectionIndex => $section) {
            $sectionData = [
                'title' => $section['title'],
                'fields' => []
            ];

            foreach ($section['fields'] as $fieldIndex => $field) {
                $fieldData = [
                    'type' => $field['type'],
                    'data' => [
                        'label' => $field['data']['label'],
                        'description' => $field['data']['description'] ?? null,
                    ],
                    'answer' => null
                ];

                // Copy any additional field-specific data
                if (isset($field['data']['type'])) {
                    $fieldData['data']['type'] = $field['data']['type'];
                }
                if (isset($field['data']['required'])) {
                    $fieldData['data']['required'] = $field['data']['required'];
                }
                if (isset($field['data']['options'])) {
                    $fieldData['data']['options'] = $field['data']['options'];
                }
                if (isset($field['data']['file_type'])) {
                    $fieldData['data']['file_type'] = $field['data']['file_type'];
                }

                // Initialize answer based on field type
                if ($field['type'] === \App\Enums\FormField::CHECKBOX->value) {
                    $fieldData['answer'] = [];
                } elseif ($field['type'] === \App\Enums\FormField::UPLOAD->value) {
                    $fieldData['answer'] = null;
                } else {
                    $fieldData['answer'] = '';
                }

                $sectionData['fields'][] = $fieldData;
            }

            $this->formData[] = $sectionData;
        }
    }

    public function submitForm()
    {
        $rules = $this->getValidationRules();
        $this->validate($rules);

        // Process answers for select, checkbox, and radio inputs to store as translatable arrays
        $this->processTranslatableAnswers();

        // Debug: Display the form data
        dd($this->formData);

        try {
            // Here you would typically save the form data to your database
            // For example:
            // $submission = EventSubmission::create([
            //     'event_id' => $this->event->id,
            //     'form_data' => $this->formData,
            //     'user_id' => auth()->id(), // If user is logged in
            // ]);

            $this->formSubmitted = true;
            $this->successMessage = __('Form submitted successfully!');

            // Optional: Reset form after successful submission
            // $this->initFormData();

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while submitting the form. Please try again.');
        }
    }

    /**
     * Process answers for select, checkbox, and radio inputs to store them as translatable arrays
     */
    protected function processTranslatableAnswers()
    {
        if (empty($this->formData)) {
            return;
        }

        foreach ($this->formData as $sectionIndex => $section) {
            if (!isset($section['fields']) || !is_array($section['fields'])) {
                continue;
            }

            foreach ($section['fields'] as $fieldIndex => $field) {
                // Skip if no answer or not a type we need to process
                if (!isset($field['answer']) || !in_array($field['type'], [
                    \App\Enums\FormField::SELECT->value,
                    \App\Enums\FormField::CHECKBOX->value,
                    \App\Enums\FormField::RADIO->value
                ])) {
                    continue;
                }

                // Process based on field type
                switch ($field['type']) {
                    case \App\Enums\FormField::SELECT->value:
                    case \App\Enums\FormField::RADIO->value:
                        // For single select/radio, find the matching option and convert to translatable array
                        $this->formData[$sectionIndex]['fields'][$fieldIndex]['answer'] =
                            $this->findOptionTranslations($field['data']['options'] ?? [], $field['answer']);
                        break;

                    case \App\Enums\FormField::CHECKBOX->value:
                        // For checkboxes (array of values), convert each selected value
                        if (is_array($field['answer'])) {
                            $translatedAnswers = [];
                            foreach ($field['answer'] as $answer) {
                                $translatedAnswers[] = $this->findOptionTranslations($field['data']['options'] ?? [], $answer);
                            }
                            $this->formData[$sectionIndex]['fields'][$fieldIndex]['answer'] = $translatedAnswers;
                        }
                        break;
                }
            }
        }
    }

    /**
     * Find the option translations for a given answer value
     *
     * @param array $options The options array from the field
     * @param string $answerValue The current answer value (in current locale)
     * @return array The translatable option array or empty array if not found
     */
    protected function findOptionTranslations(array $options, $answerValue)
    {
        $currentLocale = app()->getLocale();

        // Find the option with matching value in current locale
        foreach ($options as $option) {
            if (isset($option['option'][$currentLocale]) && $option['option'][$currentLocale] === $answerValue) {
                return $option['option'];
            }
        }

        // Fallback: Return the answer value keyed by current locale
        return [$currentLocale => $answerValue];
    }

    protected function getValidationRules()
    {
        $rules = [];

        if (!$this->event->visitorForm) {
            return $rules;
        }

        foreach ($this->event->visitorForm->sections as $sectionIndex => $section) {
            foreach ($section['fields'] as $fieldIndex => $field) {
                $fieldKey = "formData.{$sectionIndex}.fields.{$fieldIndex}.answer";
                $fieldRules = [];

                // Check if field is required
                if (Arr::get($field, 'data.required', false)) {
                    $fieldRules[] = 'required';
                } else {
                    $fieldRules[] = 'nullable';
                }

                // Add specific validation rules based on field type
                switch ($field['type']) {
                    case \App\Enums\FormField::INPUT->value:
                        switch ($field['data']['type']) {
                            case \App\Enums\FormInputType::EMAIL->value:
                                $fieldRules[] = 'email';
                                break;
                            case \App\Enums\FormInputType::NUMBER->value:
                                $fieldRules[] = 'numeric';
                                break;
                            case \App\Enums\FormInputType::PHONE->value:
                                $fieldRules[] = 'string';
                                break;
                            case \App\Enums\FormInputType::DATE->value:
                                $fieldRules[] = 'date';
                                break;
                            default:
                                $fieldRules[] = 'string';
                                break;
                        }
                        break;
                    case \App\Enums\FormField::UPLOAD->value:
                        $fieldRules[] = 'file';

                        // Add file type validation based on field definition
                        $fileType = $field['data']['file_type'] ?? \App\Enums\FileUploadType::ANY;

                        if ($fileType === \App\Enums\FileUploadType::IMAGE) {
                            $fieldRules[] = 'mimes:jpg,jpeg,png,gif,bmp,webp';
                            $fieldRules[] = 'max:10240'; // 10MB max for images
                        } elseif ($fileType === \App\Enums\FileUploadType::PDF) {
                            $fieldRules[] = 'mimes:pdf';
                            $fieldRules[] = 'max:20480'; // 20MB max for PDFs
                        } else {
                            // For any file type, set a general size limit
                            $fieldRules[] = 'max:25600'; // 25MB general limit
                        }
                        break;
                    case \App\Enums\FormField::CHECKBOX->value:
                        $fieldRules[] = 'array';
                        break;
                }

                $rules[$fieldKey] = implode('|', $fieldRules);
            }
        }

        return $rules;
    }
}; ?>

<div class="bg-white rounded-xl shadow-lg p-6">
    @if (session('error'))
        <div class="alert alert-error mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if ($formSubmitted)
        <div class="alert alert-success mb-4">
            {{ $successMessage }}
        </div>
    @else
        <form wire:submit.prevent="submitForm">
            @if ($event->visitorForm)
                @foreach ($event->visitorForm->sections as $sectionIndex => $section)
                    @include('website.components.forms.input.section_title', [
                        'title' => $section['title'][app()->getLocale()] ?? $section['title']['fr'],
                    ])

                    @foreach ($section['fields'] as $fieldIndex => $field)
                        @php
                            $answerPath = "{$sectionIndex}.fields.{$fieldIndex}.answer";
                        @endphp

                        @include("website.components.forms.fields", [
                            "fields" => [$field],
                            "answerPath" => $answerPath,
                        ])

                        @error("formData.{$sectionIndex}.fields.{$fieldIndex}.answer")
                            <div class="text-error text-sm mt-1">{{ $message }}</div>
                        @enderror
                    @endforeach

                    <div class="h-8"></div>
                @endforeach

                <div class="flex justify-end mt-6">
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ __('Submit') }}</span>
                        <span wire:loading>{{ __('Submitting...') }}</span>
                    </button>
                </div>
            @endif
        </form>
    @endif
</div>
