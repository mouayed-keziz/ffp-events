<?php

use Livewire\Volt\Component;
use App\Models\EventAnnouncement;
use App\Forms\ExhibitorFormActions;
use App\Actions\Forms\FormFieldActions;
use Livewire\WithFileUploads;
use App\Traits\Livewire\HasFormSteps;
use App\Traits\Livewire\HasPrices;
use App\Traits\Livewire\HasFormFieldUpdates;
use App\Models\ExhibitorSubmission;

new class extends Component {
    use WithFileUploads;
    use HasFormSteps;
    use HasPrices;
    use HasFormFieldUpdates;

    public EventAnnouncement $event;
    public ?ExhibitorSubmission $submission = null;
    public $disabled = false;
    public array $formData = [];
    public array $postForms = [];
    public int $currentStep = 0;
    public int $totalSteps = 0;
    public bool $formSubmitted = false;
    public string $successMessage = '';
    public string $preferred_currency = 'EUR';
    public float $totalPrice = 0;

    public function mount(EventAnnouncement $event)
    {
        $this->event = $event;

        // Find the exhibitor submission for this event for the current user
        if (auth('exhibitor')->check()) {
            $this->submission = ExhibitorSubmission::where('exhibitor_id', auth('exhibitor')->id())
                ->where('event_announcement_id', $event->id)
                ->first();
        }

        if (!$this->submission || !$this->submission->canFillPostForms) {
            session()->flash('error', 'You do not have permission to access post-payment forms for this event.');
            return redirect()->route('event_details', $event);
        }

        // Check if the submission already has post_answers and load them if available
        if ($this->submission && !empty($this->submission->post_answers)) {
            $actions = new ExhibitorFormActions();
            $this->formData = $actions->transformPostSubmissionToFormData($this->submission, $event);
        } else {
            $this->initFormData();
        }
    }

    public function updated($name)
    {
        if (str_starts_with($name, 'formData')) {
            $this->calculateTotalPrice();
        }
    }

    protected function initFormData()
    {
        $actions = new ExhibitorFormActions();
        $this->formData = $actions->initPostFormData($this->event);
        $this->totalSteps = count($this->formData);

        if ($this->totalSteps > 0) {
            $this->calculateTotalPrice();
        }
    }

    public function validateCurrentStep()
    {
        // Use the post-payment specific validation method
        $actions = new ExhibitorFormActions();
        $validation = $actions->getPostFormValidationRules($this->event, $this->currentStep);

        $this->validate($validation['rules'], [], $validation['attributes']);
    }

    public function submitForm()
    {
        $this->validateCurrentStep();
        $actions = new ExhibitorFormActions();

        // Use existing or new submission based on update or create
        if ($this->submission && !empty($this->submission->post_answers)) {
            $success = $actions->updateExistingPostSubmission($this->submission, $this->formData);
        } else {
            $success = $actions->savePostFormSubmission($this->event, $this->formData, $this->submission);
        }

        if ($success) {
            $this->formSubmitted = true;
            $this->successMessage = __('Post-exhibition form submitted successfully!');
            return redirect()->route('event_details', $this->event)->with('success', 'Post-exhibition form submitted successfully!');
        } else {
            session()->flash('error', 'An error occurred while submitting the form. Please try again.');
        }
    }

    public function isLastExhibitorForm()
    {
        return $this->currentStep === $this->totalSteps - 1;
    }
}; ?>

<div>
    <div class="container mx-auto py-8 md:px-4">
        @if (!empty($formData))
            @include('website.components.forms.multi-step-form', [
                'steps' => $formData,
                'currentStep' => $currentStep,
                'errors' => $errors,
                'formSubmitted' => $formSubmitted,
                'successMessage' => $successMessage,
            ])
        @endif

        @if (!$formSubmitted)
            @include('website.components.forms.form', ['disabled' => $disabled])

            @include('website.components.forms.price-indicator', [
                'totalPrice' => $totalPrice,
                'currency' => $preferred_currency,
                'disabled' => $disabled,
            ])
        @endif
    </div>
</div>
