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
    public string $preferred_currency = 'DZD';
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
            return redirect()->route('info_validation', ['slug' => $this->event->slug]);
        }
        $success = $actions->updateExistingSubmission($this->submission, $this->formData);

        if ($success) {
            // Log the submission update
            $user = auth()->guard('exhibitor')->user();
            \App\Activity\ExhibitorSubmissionActivity::logUpdate($user, $this->submission);

            // Get all admin and super_admin users for database notifications
            $adminUsers = \App\Models\User::role(['admin', 'super_admin'])->get();

            // Send a single email to the company email from settings
            $companySettings = app(\App\Settings\CompanyInformationsSettings::class);

            // Send email notification to company email only using notification routing
            \Illuminate\Support\Facades\Notification::route('mail', $companySettings->email)->notify(new \App\Notifications\Admin\ExhibitorSubmissionUpdate($this->event, $this->submission->exhibitor, $this->submission, true));

            // Send database notifications to all admins and super_admins
            foreach ($adminUsers as $admin) {
                // Send database notification only
                $admin->notify(new \App\Notifications\Admin\ExhibitorSubmissionUpdate($this->event, $this->submission->exhibitor, $this->submission));

                // Also send direct database notification for Filament panel
                \Filament\Notifications\Notification::make()
                    ->title("Mise à jour de soumission d'exposant")
                    ->body("L'exposant {$this->submission->exhibitor->name} a mis à jour sa soumission pour l'événement {$this->event->title}.")
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('voir')
                            ->label('Voir la soumission')
                            ->url(route('filament.admin.resources.exhibitor-submissions.view', $this->submission->id)),
                    ])
                    ->icon('heroicon-o-pencil-square')
                    ->iconColor('warning')
                    ->sendToDatabase($admin);

                \Illuminate\Support\Facades\Log::info("Admin notification sent to: {$admin->email} for submission update");
            }

            return redirect()->route('info_validation', ['slug' => $this->event->slug]);
        } else {
            session()->flash('error', 'An error occurred while updating the form. Please try again.');
        }
    }

    public function requestFormModification()
    {
        $this->submission->update_requested_at = now();
        $this->submission->save();

        // Log the update request
        $user = auth()->guard('exhibitor')->user();
        \App\Activity\ExhibitorSubmissionActivity::logRequestUpdate($user, $this->submission);

        // Get all admin and super_admin users for database notifications
        $adminUsers = \App\Models\User::role(['admin', 'super_admin'])->get();

        // Send a single email to the company email from settings
        $companySettings = app(\App\Settings\CompanyInformationsSettings::class);

        // Send email notification to company email only using notification routing
        \Illuminate\Support\Facades\Notification::route('mail', $companySettings->email)->notify(new \App\Notifications\Admin\ExhibitorModificationRequest($this->event, $this->submission->exhibitor, $this->submission, true));

        // Send database notifications to all admins and super_admins
        foreach ($adminUsers as $admin) {
            // Send database notification only
            $admin->notify(new \App\Notifications\Admin\ExhibitorModificationRequest($this->event, $this->submission->exhibitor, $this->submission));

            // Also send direct database notification for Filament panel
            \Filament\Notifications\Notification::make()
                ->title('Demande de modification de formulaire')
                ->body("L'exposant {$this->submission->exhibitor->name} a demandé à modifier sa soumission pour l'événement {$this->event->title}.")
                ->actions([
                    \Filament\Notifications\Actions\Action::make('voir')
                        ->label('Voir la soumission')
                        ->url(route('filament.admin.resources.exhibitor-submissions.view', $this->submission->id)),
                ])
                ->icon('heroicon-o-clipboard-document-check')
                ->iconColor('warning')
                ->sendToDatabase($admin);

            \Illuminate\Support\Facades\Log::info("Admin notification sent to: {$admin->email} for form modification request");
        }

        session()->flash('success', __('Form modification request submitted successfully'));
        $this->dispatch('refreshPage');
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
            'disabled' => false,
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
