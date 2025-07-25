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
    public string $badgeEmail = '';
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
            return redirect()->route('event_details', ['id' => $this->event->id]);
        }
    }

    protected function initFormData()
    {
        $actions = new VisitEventFormActions();
        // For anonymous form, don't check for existing submissions
        $this->formData = $actions->initFormData($this->event);
    }

    public function updateRadioSelection($answerPath, $selectedValue)
    {
        $this->formData = FormFieldActions::updateOptionSelection($this->formData, $answerPath, $selectedValue);
    }

    public function updateSelectOption($answerPath, $selectedValue)
    {
        $this->formData = FormFieldActions::updateOptionSelection($this->formData, $answerPath, $selectedValue);

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
        $this->badgeEmail = '';
    }

    public function submitWithBadgeInfo()
    {
        // Validate badge information including email for anonymous submissions
        $this->validate(
            [
                'badgeCompany' => 'required|string|max:255',
                'badgePosition' => 'required|string',
                'badgeEmail' => 'required|email',
            ],
            [
                'badgeCompany.required' => __('website/visit-event.company_required'),
                'badgePosition.required' => __('website/visit-event.position_required'),
                'badgeEmail.required' => __('website/visit-event.email_required'),
                'badgeEmail.email' => __('website/visit-event.email_invalid'),
            ],
        );

        $actions = new VisitEventFormActions();

        // Save the form submission with badge information and email for anonymous user
        $success = $actions->saveAnonymousFormSubmission($this->event, $this->formData, $this->badgeCompany, $this->badgePosition, $this->badgeEmail);

        if ($success) {
            // Get the latest anonymous submission for this event
            $submission = VisitorSubmission::where('event_announcement_id', $this->event->id)->where('anonymous_email', $this->badgeEmail)->latest()->first();

            // Log the anonymous visitor submission
            \App\Activity\VisitorSubmissionActivity::logAnonymousCreate($submission);

            $this->formSubmitted = true;
            $this->redirect(route('visit_event_anonymous_form_submitted', $this->event->id));
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
        {{-- Anonymous form header --}}
        {{-- <div class="mb-6 bg-info text-info-content p-4 rounded-lg">
            <h2 class="text-xl font-bold mb-2">{{ __('website/visit-event.anonymous_registration') }}</h2>
            <p class="text-sm">{{ __('website/visit-event.anonymous_fill_form_instruction') }}</p>
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

    {{-- Badge Information Modal with Email Field --}}
    @if ($showBadgeInfoModal)
        <div class="modal modal-open">
            <div class="modal-box w-11/12 max-w-md">
                <h3 class="font-bold text-lg mb-4">{{ __('website/visit-event.badge_info_title') }}</h3>
                <p class="text-sm text-gray-600 mb-6">{{ __('website/visit-event.badge_info_description_anonymous') }}
                </p>

                <form wire:submit.prevent="submitWithBadgeInfo">
                    {{-- Email Input for Anonymous Users --}}
                    <div class="form-control w-full mb-4">
                        <label class="label">
                            <span class="label-text">{{ __('website/visit-event.email') }} <span
                                    class="text-red-500">*</span></span>
                        </label>
                        <input type="email" wire:model="badgeEmail"
                            placeholder="{{ __('website/visit-event.email_placeholder') }}"
                            class="input input-bordered w-full @error('badgeEmail') input-error @enderror" required />
                        @error('badgeEmail')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    {{-- Company Input --}}
                    <div class="form-control w-full mb-4">
                        <label class="label">
                            <span class="label-text">{{ __('website/visit-event.company') }} <span
                                    class="text-red-500">*</span></span>
                        </label>
                        <input type="text" wire:model="badgeCompany"
                            placeholder="{{ __('website/visit-event.company_placeholder') }}"
                            class="input input-bordered w-full @error('badgeCompany') input-error @enderror" required />
                        @error('badgeCompany')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    {{-- Position Select --}}
                    <div class="form-control w-full mb-6">
                        <label class="label">
                            <span class="label-text">{{ __('website/visit-event.position') }} <span
                                    class="text-red-500">*</span></span>
                        </label>
                        <select wire:model="badgePosition"
                            class="select select-bordered w-full @error('badgePosition') select-error @enderror"
                            required>
                            <option value="">{{ __('website/visit-event.position_placeholder') }}</option>
                            @foreach ($availableJobs as $job)
                                <option value="{{ $job['fr'] }}">{{ $job[app()->getLocale()] ?? $job['fr'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('badgePosition')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    {{-- Modal Actions --}}
                    <div class="modal-action">
                        <button type="button" wire:click="cancelBadgeInfo" class="btn btn-ghost">
                            {{ __('website/visit-event.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading class="loading loading-spinner loading-sm"></span>
                            <span>{{ __('website/visit-event.continue_registration') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
