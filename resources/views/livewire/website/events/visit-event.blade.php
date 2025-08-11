<?php
use Livewire\Volt\Component;
use App\Models\EventAnnouncement;
use App\Models\VisitorSubmission;
use App\Forms\VisitEventFormActions;
use App\Actions\Forms\FormFieldActions;
use App\Settings\CompanyInformationsSettings;
use Livewire\WithFileUploads;
use App\Traits\Livewire\HasFormFieldUpdates;

new class extends Component {
    use WithFileUploads;
    use HasFormFieldUpdates;

    // Event and form data

    public EventAnnouncement $event;
    public array $formData = [];
    public bool $formSubmitted = false;
    public string $successMessage = '';

    // Badge information popup
    public bool $showBadgeInfoModal = false;
    public string $badgeCompany = '';
    public string $badgePosition = '';
    public array $availableJobs = [];

    public function mount(EventAnnouncement $event)
    {
        $this->event = $event;
        $this->initFormData();

        // Load available jobs from settings
        $settings = app(CompanyInformationsSettings::class);
        $this->availableJobs = $settings->jobs ?? [];

        // Redirect to event page if no visitor form available
        if (empty($this->formData)) {
            return redirect()->route('event_details', ['slug' => $this->event->slug]);
        }
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
        $this->formData = FormFieldActions::updateOptionSelection($this->formData, $answerPath, $selectedValue);
    }

    public function updateSelectOption($answerPath, $selectedValue)
    {
        // Handle custom inline badge select locally
        if (str_starts_with($answerPath, 'badge.')) {
            $options = data_get($this->formData, $answerPath . '.options', []);
            foreach ($options as $idx => $opt) {
                $options[$idx]['selected'] = ($opt['value'] ?? null) === $selectedValue;
            }
            data_set($this->formData, $answerPath . '.options', $options);
        } else {
            $this->formData = FormFieldActions::updateOptionSelection($this->formData, $answerPath, $selectedValue);
        }

        // Dispatch a browser event to inform Alpine.js of the change
        $this->dispatch('selectedchanged', [
            'path' => $answerPath,
            'label' => $selectedValue,
        ]);
    }

    public function submitForm()
    {
        $actions = new VisitEventFormActions();

        // Validate the form data with proper attributes
        $validation = $actions->getValidationRules($this->event);
        $this->validate($validation['rules'], [], $validation['attributes']);

        // Show badge information modal instead of immediately saving
        $this->showBadgeInfoModal = true;
    }

    public function cancelBadgeInfo()
    {
        $this->showBadgeInfoModal = false;
        $this->badgeCompany = '';
        $this->badgePosition = '';
    }

    public function submitWithBadgeInfo()
    {
        // Validate full form: event form data + badge info
        $actions = new VisitEventFormActions();
        $validation = $actions->getValidationRules($this->event);
        $this->validate($validation['rules'], [], $validation['attributes']);

        // Validate badge data stored in formData
        $this->validate(
            [
                'formData.badge.company' => 'required|string|max:255',
            ],
            [
                'formData.badge.company.required' => __('website/visit-event.company_required'),
            ],
        );

        // Sync inline badge fields from formData to props
        $this->badgeCompany = data_get($this->formData, 'badge.company', '');
        // Extract selected position from options
        $selected = collect(data_get($this->formData, 'badge.position.options', []))->firstWhere('selected', true);
        $this->badgePosition = $selected['value'] ?? '';
        if (empty($this->badgePosition)) {
            $this->addError('formData.badge.position', __('website/visit-event.position_required'));
            return;
        }

        // The badgePosition already contains the French job title since we store 'fr' value in the select
        // Save the form submission with badge information
        $success = $actions->saveFormSubmission($this->event, $this->formData, $this->badgeCompany, $this->badgePosition);

        if ($success) {
            // Get the visitor user and submission
            $user = auth()->guard('visitor')->user();
            $submission = \App\Models\VisitorSubmission::where('event_announcement_id', $this->event->id)->where('visitor_id', $user->id)->latest()->first();

            // Log the visitor submission
            \App\Activity\VisitorSubmissionActivity::logCreate($user, $submission);

            $this->formSubmitted = true;
            $this->redirect(route('visit_event_form_submitted', ['slug' => $this->event->slug]));
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
        {{-- <div class="rounded-btn alert alert-success mb-4 shadow-md text-white">
            <x-heroicon-o-check-circle class="w-6 h-6 inline-block mr-2" />
            {{ $successMessage }}
        </div> --}}
    @else
        {{-- <div class="mb-6">
            <h2 class="text-2xl font-bold mb-2">{{ __('website/visit-event.visitor_registration') }}</h2>
            <p class="text-gray-600">{{ __('website/visit-event.fill_form_instruction') }}</p>
        </div> --}}

        <form wire:submit.prevent="submitWithBadgeInfo">
            @if ($event->visitorForm)
                {{-- Inline Badge Information Fields (moved from modal) using shared components --}}
                @php
                    $badgeCompanyField = [
                        'label' => [app()->getLocale() => __('website/visit-event.company')],
                        'required' => true,
                        'type' => 'text',
                    ];
                    $jobOptions = array_map(fn($job) => ['option' => $job], $availableJobs);
                    $badgePositionField = [
                        'label' => [app()->getLocale() => __('website/visit-event.position')],
                        'required' => true,
                        'options' => $jobOptions,
                    ];
                @endphp
                <div class="grid grid-cols-1 gap-4 mb-6">
                    @include('website.components.forms.input.text-input', [
                        'data' => $badgeCompanyField,
                        'answerPath' => 'badge.company',
                        'disabled' => false,
                    ])
                    @include('website.components.forms.multiple.select', [
                        'data' => $badgePositionField,
                        'answerPath' => 'badge.position',
                        'disabled' => false,
                    ])
                </div>

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

                        <div class="flex justify-start">
                            <button type="submit" class="btn font-semibold btn-sm rounded-md btn-primary"
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

    {{-- Modal removed: badge fields are now inline at the top of the form --}}
</div>
