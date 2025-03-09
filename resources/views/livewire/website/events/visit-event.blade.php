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
    public bool $terms_accepted = false;

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
        $this->formData = $actions->initFormData($this->event);
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

        // Validate the form data with proper attributes
        $validation = $actions->getValidationRules($this->event);
        $this->validate($validation['rules'], [], $validation['attributes']);

        // Add terms acceptance validation
        $this->validate(
            [
                'terms_accepted' => 'accepted',
            ],
            [],
            [
                'terms_accepted' => __('website/visit-event.terms_and_conditions'),
            ],
        );

        // Save the form submission
        $success = $actions->saveFormSubmission($this->event, $this->formData);

        if ($success) {
            $this->formSubmitted = true;
            $this->successMessage = __('website/visit-event.form_success');
        } else {
            session()->flash('error', __('website/visit-event.form_error'));
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
            <h2 class="text-2xl font-bold mb-2">{{ __('website/visit-event.visitor_registration') }}</h2>
            <p class="text-gray-600">{{ __('website/visit-event.fill_form_instruction') }}</p>
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

                        {{-- Debug information to help troubleshoot --}}
                        @include('website.components.forms.debug-path', [
                            'answerPath' => $answerPath,
                        ])
                    @endforeach

                    <div class="h-8"></div>
                @endforeach

                <div class="flex justify-start mt-6">
                    <div class="flex flex-col gap-4 w-full">
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

                        <div class="flex justify-start">
                            <button type="submit" class="btn btn-md rounded-md btn-primary"
                                wire:loading.attr="disabled">
                                <span wire:loading class="loading loading-spinner"></span>
                                <span>{{ __('website/visit-event.submit') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <p class="text-center text-gray-500">{{ __('website/visit-event.no_form_available') }}</p>
                </div>
            @endif
        </form>
    @endif
</div>
