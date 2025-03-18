<?php

use Livewire\Volt\Component;
use App\Models\EventAnnouncement;
use App\Models\ExhibitorSubmission;
use App\Forms\ExhibitorFormActions;
use App\Actions\Forms\FormFieldActions;
use Livewire\WithFileUploads;
use App\Traits\Livewire\HasFormSteps;
use App\Traits\Livewire\HasPrices;
use App\Traits\Livewire\HasFormFieldUpdates;

new class extends Component {
    use WithFileUploads;
    use HasFormSteps;
    use HasPrices;
    use HasFormFieldUpdates;

    public EventAnnouncement $event;
    public ExhibitorSubmission $submission;
    public $disabled = true;
    public array $formData = [];
    public array $postForms = [];
    public int $currentStep = 0;
    public int $totalSteps = 0;
    public bool $formSubmitted = false;
    public string $successMessage = '';
    public string $preferred_currency = 'EUR';
    public float $totalPrice = 0;

    public function mount(EventAnnouncement $event, ExhibitorSubmission $submission)
    {
        $this->event = $event;
        $this->submission = $submission;
        $this->initFormData();
        $this->postForms = $event->exhibitorPostPaymentForms->toArray();
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
        // Use the new transformSubmissionToFormData method instead of directly accessing answers
        $this->formData = $actions->transformSubmissionToFormData($this->submission, $this->event);
        $this->totalSteps = count($this->formData);
        $this->disabled = !$this->submission->isEditable;

        if ($this->totalSteps > 0) {
            $this->calculateTotalPrice();
        }
    }

    public function submitForm()
    {
        $this->validateCurrentStep();
        $actions = new ExhibitorFormActions();

        // Use the new updateExistingSubmission method
        if ($this->disabled) {
            return redirect()->route('info_validation', ['id' => $this->event->id]);
        }
        $success = $actions->updateExistingSubmission($this->submission, $this->formData);

        if ($success) {
            // $this->formSubmitted = true;
            // $this->successMessage = __('exhibitor_submission.messages.updated');
            // Refresh the page after a brief delay to show the success message
            return redirect()->route('info_validation', ['id' => $this->event->id]);
        } else {
            session()->flash('error', 'An error occurred while updating the form. Please try again.');
        }
    }

    public function requestFormModification()
    {
        $this->submission->update_requested_at = now();
        $this->submission->save();

        // session()->flash('success', __('Form modification request submitted successfully'));
        // $this->dispatch('refreshPage');
    }

    public function isLastExhibitorForm()
    {
        return $this->currentStep === $this->totalSteps - 1;
    }
}; ?>

<div class="container mx-auto py-8 md:px-4">
    @if ($formSubmitted)
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ $successMessage }}</span>
        </div>
    @endif

    @if (!empty($formData))
        @include('website.components.forms.multi-step-form', [
            'steps' => $formData,
            'postForms' => $postForms,
            'currentStep' => $currentStep,
            'errors' => $errors,
            'formSubmitted' => $formSubmitted,
            'successMessage' => $successMessage,
        ])
    @endif

    @if (!$formSubmitted)
        @include('website.components.forms.form', ['disabled' => $disabled])
        <!-- Floating Price Indicator Component with Currency Selector -->
        @include('website.components.forms.price-indicator', [
            'totalPrice' => $totalPrice,
            'currency' => $preferred_currency,
            'disabled' => $disabled,
        ])
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('refreshPage', () => {
            setTimeout(() => {
                location.reload();
            }, 1500);
        });
    });
</script>
