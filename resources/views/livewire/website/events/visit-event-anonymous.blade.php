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

    public ?string $fbc = null;
    public ?string $fbp = null;

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
    public string $badgeName = '';
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
        // For anonymous form, don't check for existing submissions
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
        $this->badgeEmail = '';
        $this->badgeName = '';
    }

    public function submitWithBadgeInfo()
    {
        // Validate full form: event form data + badge
        $actions = new VisitEventFormActions();
        $validation = $actions->getValidationRules($this->event);
        $this->validate($validation['rules'], [], $validation['attributes']);

        // Validate badge information stored in formData
        $this->validate([
            'formData.badge.first_name' => 'required|string|max:255',
            'formData.badge.last_name' => 'required|string|max:255',
            'formData.badge.company' => 'required|string|max:255',
            'formData.badge.email' => 'required|email',
        ]);

        // Sync badge fields from formData to props and ensure position selected
        $first = data_get($this->formData, 'badge.first_name', '');
        $last = data_get($this->formData, 'badge.last_name', '');
        $this->badgeName = trim($first . ' ' . $last);
        $this->badgeEmail = data_get($this->formData, 'badge.email', '');
        $this->badgeCompany = data_get($this->formData, 'badge.company', '');
        $selected = collect(data_get($this->formData, 'badge.position.options', []))->firstWhere('selected', true);
        $this->badgePosition = $selected['value'] ?? '';
        if (empty($this->badgePosition)) {
            $this->addError('formData.badge.position', 'Le poste est requis');
            return;
        }

        // Save the form submission with badge information and email for anonymous user
        $success = $actions->saveAnonymousFormSubmission($this->event, $this->formData, $this->badgeCompany, $this->badgePosition, $this->badgeEmail, $this->badgeName);

        if ($success) {
            // Get the latest anonymous submission for this event
            $submission = VisitorSubmission::where('event_announcement_id', $this->event->id)->where('anonymous_email', $this->badgeEmail)->latest()->first();

            // Log the anonymous visitor submission
            \App\Activity\VisitorSubmissionActivity::logAnonymousCreate($submission);
            // Collect Meta Pixel args then dd them; later call the method
            $clientIp = request()->ip();
            $fullName = $this->badgeName;
            $firstName = $fullName ? explode(' ', $fullName, 2)[0] : null;
            $lastName = $fullName ? explode(' ', $fullName, 2)[1] ?? null : null;
            $email = $this->badgeEmail;
            $phone = null; // anonymous flow has no phone
            \App\Activity\VisitorSubmissionActivity::sendMetaPixelCompleteRegistrationAnonymous($clientIp, $firstName, $lastName, $email, $phone, null, $this->fbc, $this->fbp);
            // dd($clientIp, $firstName, $lastName, $email, $phone);
            $this->formSubmitted = true;
            $this->redirect(route('visit_event_anonymous_form_submitted', ['slug' => $this->event->slug]));
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

        <form wire:submit.prevent="submitWithBadgeInfo" x-data x-init="(() => {
            // Helper to get cookie
            let getCookie = (name) => {
                let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
                return match ? match[2] : null;
            };
        
            // Helper to set cookie (expire in 90 days)
            let setCookie = (name, value) => {
                let d = new Date();
                d.setTime(d.getTime() + (90 * 24 * 60 * 60 * 1000));
                document.cookie = name + '=' + encodeURIComponent(value) + ';path=/;expires=' + d.toUTCString();
            };
        
            let syncFbCookies = () => {
                // Parse fbclid from URL
                const urlParams = new URLSearchParams(window.location.search);
                const fbclid = urlParams.get('fbclid');
        
                let fbc = getCookie('_fbc');
                let fbp = getCookie('_fbp');
        
                // Generate fbc if fbclid is present
                if (fbclid) {
                    const timestamp = Math.floor(Date.now() / 1000);
                    fbc = `fb.1.${timestamp}.${fbclid}`;
                    setCookie('_fbc', fbc);
                }
        
                // Generate fbp if missing
                if (!fbp) {
                    const randomId = Math.floor(Math.random() * 1e10);
                    fbp = `fb.1.${Math.floor(Date.now() / 1000)}.${randomId}`;
                    setCookie('_fbp', fbp);
                }
        
                // Push to Livewire
                $wire.$set('fbc', fbc || null);
                $wire.$set('fbp', fbp || null);
            };
        
            // Run once on init
            syncFbCookies();
        
            // Optional: keep in sync if user navigates without reload
            setInterval(syncFbCookies, 5000);
        })()">

            <input type="hidden" wire:model="fbc" id="fbc">
            <input type="hidden" wire:model="fbp" id="fbp">

            @if ($event->visitorForm)
                @php $hasSections = !empty($event->visitorForm->sections); @endphp
                @if (!$hasSections)
                    @php
                        $badgeFirstNameField = [
                            'label' => [app()->getLocale() => __('website/visit-event.first_name')],
                            'required' => true,
                            'type' => 'text',
                        ];
                        $badgeLastNameField = [
                            'label' => [app()->getLocale() => __('website/visit-event.last_name')],
                            'required' => true,
                            'type' => 'text',
                        ];
                        $badgeEmailField = [
                            'label' => [app()->getLocale() => __('website/visit-event.email')],
                            'required' => true,
                            'type' => 'email',
                        ];
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        @include('website.components.forms.input.text-input', [
                            'data' => $badgeFirstNameField,
                            'answerPath' => 'badge.first_name',
                            'disabled' => false,
                        ])
                        @include('website.components.forms.input.text-input', [
                            'data' => $badgeLastNameField,
                            'answerPath' => 'badge.last_name',
                            'disabled' => false,
                        ])
                        @include('website.components.forms.input.email-input', [
                            'data' => $badgeEmailField,
                            'answerPath' => 'badge.email',
                            'disabled' => false,
                        ])
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
                @endif
                @foreach ($event->visitorForm->sections as $sectionIndex => $section)
                    @include('website.components.forms.input.section_title', [
                        'title' => $section['title'][app()->getLocale()] ?? $section['title']['fr'],
                    ])
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if ($sectionIndex === 0)
                            @php
                                $badgeFirstNameField = [
                                    'label' => [app()->getLocale() => __('website/visit-event.first_name')],
                                    'required' => true,
                                    'type' => 'text',
                                ];
                                $badgeLastNameField = [
                                    'label' => [app()->getLocale() => __('website/visit-event.last_name')],
                                    'required' => true,
                                    'type' => 'text',
                                ];
                                $badgeEmailField = [
                                    'label' => [app()->getLocale() => __('website/visit-event.email')],
                                    'required' => true,
                                    'type' => 'email',
                                ];
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
                            @include('website.components.forms.input.text-input', [
                                'data' => $badgeFirstNameField,
                                'answerPath' => 'badge.first_name',
                                'disabled' => false,
                            ])
                            @include('website.components.forms.input.text-input', [
                                'data' => $badgeLastNameField,
                                'answerPath' => 'badge.last_name',
                                'disabled' => false,
                            ])
                            @include('website.components.forms.input.email-input', [
                                'data' => $badgeEmailField,
                                'answerPath' => 'badge.email',
                                'disabled' => false,
                            ])
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
                        @endif

                        @foreach ($section['fields'] as $fieldIndex => $field)
                            @php
                                $answerPath = "{$sectionIndex}.fields.{$fieldIndex}.answer";
                                // Determine field type; adjust keys if structure differs
                                $fieldType =
                                    $field['type'] ?? ($field['field_type'] ?? ($field['data']['type'] ?? null));
                                // Detect paragraph-style input (textarea) via common keys
                                $paragraphIndicators = [
                                    $field['input_type'] ?? null,
                                    $field['subtype'] ?? null,
                                    $field['style'] ?? null,
                                    $field['variant'] ?? null,
                                    $field['mode'] ?? null,
                                    $field['data']['input_type'] ?? null,
                                ];
                                $isParagraphInput =
                                    $fieldType === 'input' && in_array('paragraph', $paragraphIndicators, true);
                                $fullWidthTypes = ['checkbox', 'radio', 'upload'];
                                $isFullWidth = in_array($fieldType, $fullWidthTypes, true) || $isParagraphInput;
                            @endphp
                            <div class="{{ $isFullWidth ? 'md:col-span-2' : '' }}">
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
                            </div>
                        @endforeach
                    </div>
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
