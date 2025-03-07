<?php
use Livewire\Volt\Component;
use App\Models\EventAnnouncement;
use App\Models\VisitorSubmission;
use App\Forms\VisitEventFormActions;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    // Event and form data
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
        $actions = new VisitEventFormActions();
        $user = auth()->guard('visitor')->user();
        $submission = VisitorSubmission::where('event_announcement_id', $this->event->id)->where('visitor_id', $user->id)->first();
        // if ($submission) {

        // }
        $this->formData = $actions->initFormData($this->event);
        $this->formData = $submission->answers;
    }

    public function updateRadioSelection($answerPath, $selectedValue)
    {
        // Store the selected value in the selectedValue property
        data_set($this, 'formData.' . $answerPath . '.selectedValue', $selectedValue);

        // Get the options array from the answer path
        $options = data_get($this, 'formData.' . $answerPath . '.options', []);
        if (empty($options)) {
            return;
        }

        // Update the options to ensure only the selected one has selected=true
        foreach ($options as $index => $option) {
            $isCurrentOptionSelected = $option['value'] == $selectedValue;
            $options[$index]['selected'] = $isCurrentOptionSelected;
        }

        // Set the updated options back to the form data
        data_set($this, 'formData.' . $answerPath . '.options', $options);

        // Recalculate price after changing selection
        // $this->calculateTotalPrice();
    }

    /**
     * Update select option for SELECT_PRICED field
     *
     * @param string $answerPath The path to the answer in formData
     * @param string $selectedValue The value of the selected option
     */
    public function updateSelectOption($answerPath, $selectedValue)
    {
        // Get the options array from the answer path
        $options = data_get($this, 'formData.' . $answerPath . '.options', []);
        if (empty($options)) {
            return;
        }

        // Update the options to ensure only the selected one has selected=true
        foreach ($options as $index => $option) {
            $isCurrentOptionSelected = $option['value'] == $selectedValue;
            $options[$index]['selected'] = $isCurrentOptionSelected;
        }

        // Set the updated options back to the form data
        data_set($this, 'formData.' . $answerPath . '.options', $options);

        // Dispatch a browser event to inform Alpine.js of the change
        $this->dispatch('selectedchanged', [
            'path' => $answerPath,
            'label' => $selectedValue,
        ]);

        // Recalculate price after changing selection
    }

    public function submitForm()
    {
        $actions = new VisitEventFormActions();

        // Validate the form data
        $rules = $actions->getValidationRules($this->event);
        $this->validate($rules);

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
        </div>
    @else
        {{-- <div class="mb-6">
            <h2 class="text-2xl font-bold mb-2">{{ __('Visitor Registration') }}</h2>
            <p class="text-gray-600">{{ __('Please fill out the form below to register for this event.') }}</p>
        </div> --}}

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

                        @include('website.components.forms.fields', [
                            'fields' => [$field],
                            'answerPath' => $answerPath,
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
                        <span wire:loading wire:target="submitForm">
                            <x-heroicon-o-arrow-path class="w-5 h-5 animate-spin mr-2" />
                            {{ __('Submitting...') }}
                        </span>
                    </button>
                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <p class="text-center text-gray-500">{{ __('No form available for this event.') }}</p>
                </div>
            @endif
        </form>
    @endif
</div>
