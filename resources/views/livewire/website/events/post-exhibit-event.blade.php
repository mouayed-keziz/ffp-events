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
    // use HasFormSteps;
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
    public string $preferred_currency = 'DZD';
    public float $totalPrice = 0;
    public int $formStep = 0;

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
            session()->flash('error', __('You do not have permission to access post-payment forms for this event.'));
            return redirect()->route('event_details', $event);
        }

        // Get the regular form data for progress display
        $actions = new ExhibitorFormActions();
        $regularForms = $actions->initFormData($this->event);

        // Get post forms
        $this->postForms = $event->exhibitorPostPaymentForms->toArray();

        // Calculate form step for internal use (0-indexed for post forms)
        $this->formStep = 0;

        // Set current step to the first post-form (after forms, info validation, payment, payment validation)
        $this->currentStep = count($regularForms) + 3;

        // Check if the submission already has post_answers and load them if available
        if ($this->submission && !empty($this->submission->post_answers)) {
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
        $validation = $actions->getPostFormValidationRules($this->event, $this->formStep);

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
            // Send notification to admin users with super_admin role
            $adminUsers = \App\Models\User::role('super_admin')->get();
            foreach ($adminUsers as $admin) {
                // Send Laravel notification for email
                $admin->notify(new \App\Notifications\Admin\ExhibitorPostSubmission($this->event, $this->submission->exhibitor, $this->submission));

                // Send direct database notification for Filament panel
                \Filament\Notifications\Notification::make()
                    ->title('Formulaire post-paiement soumis')
                    ->body("L'exposant {$this->submission->exhibitor->name} a soumis un formulaire post-paiement pour l'événement {$this->event->title}.")
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('voir')
                            ->label('Voir la soumission')
                            ->url(route('filament.admin.resources.exhibitor-submissions.view', $this->submission->id)),
                    ])
                    ->icon('heroicon-o-document-check')
                    ->iconColor('warning')
                    ->sendToDatabase($admin);

                \Illuminate\Support\Facades\Log::info("Admin notification sent to: {$admin->email} for post-payment form submission");
            }

            $this->formSubmitted = true;
            $this->successMessage = __('website/exhibit-event.post_form_submitted_success');
            return redirect()->route('event_details', $this->event)->with('success', __('website/exhibit-event.post_form_submitted_success'));
        } else {
            session()->flash('error', __('An error occurred while submitting the form. Please try again.'));
        }
    }

    public function nextStep()
    {
        $this->validateCurrentStep();

        $this->formStep++;
        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->formStep = max(0, $this->formStep - 1);
        $this->currentStep = max(count((new ExhibitorFormActions())->initFormData($this->event)) + 3, $this->currentStep - 1);
    }

    public function isLastExhibitorForm()
    {
        // Check if this is the last post form
        return $this->formStep === count($this->postForms) - 1;
    }
}; ?>

<div>
    <div class="container mx-auto py-8 md:px-4">
        @if (!empty($formData))
            @php
                $regularForms = (new \App\Forms\ExhibitorFormActions())->initFormData($event);
            @endphp

            @include('website.components.forms.multi-step-form', [
                'steps' => $regularForms,
                'postForms' => $postForms,
                'currentStep' => $currentStep,
                'errors' => $errors,
                'formSubmitted' => $formSubmitted,
                'successMessage' => $successMessage,
            ])
        @endif

        @if (!$formSubmitted)
            @include('website.components.forms.form', [
                'disabled' => $disabled,
                'formStep' => $formStep ?? 0,
            ])

            @include('website.components.forms.price-indicator', [
                'totalPrice' => $totalPrice,
                'currency' => $preferred_currency,
                'disabled' => $disabled,
            ])
        @endif
    </div>
</div>
