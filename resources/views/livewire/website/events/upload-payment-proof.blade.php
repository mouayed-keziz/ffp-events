<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Enums\PaymentSliceStatus;
use App\Enums\ExhibitorSubmissionStatus;

new class extends Component {
    use WithFileUploads;

    public $event;
    public $submission;
    public $payments;
    public $currentPayment;
    public $formData = [
        'payment_proof' => null,
    ];

    public function mount($event, $submission)
    {
        $this->event = $event;
        $this->submission = $submission;
        $this->payments = $this->submission->paymentSlices;

        // Get the next unpaid payment (closest due date)
        $this->currentPayment = $this->payments->where('status', PaymentSliceStatus::NOT_PAYED)->sortBy('due_to')->first();
    }

    public function submitPaymentProof()
    {
        $this->validate(
            [
                'formData.payment_proof' => 'required|file|max:10240', // 10MB max
            ],
            [
                'formData.payment_proof.required' => __('Le fichier de preuve de paiement est requis'),
            ],
        );

        if ($this->currentPayment) {
            // Update payment status
            $this->currentPayment->status = PaymentSliceStatus::PROOF_ATTACHED;
            $this->currentPayment->save();

            // Store the file in the media collection
            $this->currentPayment->attachMedia($this->formData['payment_proof']);
            $this->currentPayment->save();

            // Update submission status to PARTLY_PAYED
            $this->submission->status = ExhibitorSubmissionStatus::PARTLY_PAYED;
            $this->submission->save();

            // Redirect to info validation page
            return redirect()
                ->route('payment_validation', [
                    'id' => $this->event->id,
                    // 'submission' => $this->submission->id,
                ])
                ->with('success', 'Preuve de paiement téléversée avec succès');
        }
    }
}; ?>

<div class="pt-8">
    @php
        $actions = new \App\Forms\ExhibitorFormActions();
        $formData = $actions->initFormData($event);
        $postForms = $event->exhibitorPostPaymentForms->toArray();
        $currentStep = count($formData) + 1; // Payment step is after forms and info validation
    @endphp

    @include('website.components.forms.multi-step-form', [
        'steps' => $formData,
        'postForms' => $postForms,
        'currentStep' => $currentStep,
        'errors' => $errors ?? null,
        'formSubmitted' => false,
        'successMessage' => '',
    ])

    <h2 class="text-xl font-semibold mb-6">Preuve du paiement</h2>

    @if ($currentPayment)
        <div class="mb-8">


            <div class="mb-6 text-gray-500">
                <p class="mb-4">
                    Veuillez téléverser votre preuve de paiement ici de
                    <span
                        class="font-bold">{{ number_format($currentPayment->price, 0, ',', ' ') }}{{ $currentPayment->currency }}</span>,
                    le deadline de paiement est le <span
                        class="font-bold">{{ $currentPayment->due_to->format('d-m-Y') }}</span> notre
                    équipe vous
                    contactera dans les
                    brefs délais pour confirmer le paiement et l'inscription, une fois l'inscription confirmé par notre
                    équipe vous
                    allez pouvoir générer les badge de votre équipe via la page <a
                        class="font-bold link link-primary">mes
                        inscriptions.</a>
                </p>

                @if ($payments->where('status', PaymentSliceStatus::NOT_PAYED)->count() > 1)
                    <p class="mb-3">Vous avez d'autres paiements divisés par tranche avec cet ordre:</p>
                    <ul class="list-disc pl-5 mb-4">
                        @foreach ($payments->where('status', PaymentSliceStatus::NOT_PAYED)->where('id', '!=', $currentPayment->id)->sortBy('due_to') as $payment)
                            <li>Montant: <span
                                    class="font-bold">{{ number_format($payment->price, 0, ',', ' ') }}{{ $payment->currency }}</span>,
                                Deadline: <span class="font-bold">{{ $payment->due_to->format('d M Y') }}</span></li>
                        @endforeach
                    </ul>
                @endif

                <p>Veuillez nous contacter en cas de besoin.</p>
            </div>

            <form wire:submit="submitPaymentProof">
                @include('website.components.forms.file-upload', [
                    'data' => [
                        'label' => ['fr' => 'Preuve de paiement', 'en' => 'Payment proof', 'ar' => 'إثبات الدفع'],
                        'required' => true,
                        'file_type' => \App\Enums\FileUploadType::ANY->value,
                    ],
                    'answerPath' => 'payment_proof',
                    'disabled' => false,
                ])
                @error('formData.payment_proof')
                    <div class="text-error text-sm mt-1">{{ $message }}</div>
                @enderror

                <div class="mt-6">
                    <button type="submit" class="btn btn-primary">
                        Soumettre la preuve de paiement
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="p-4 bg-success/10 text-success rounded-lg">
            <p class="font-medium">Tous vos paiements ont été traités ou sont en cours de vérification.</p>
        </div>
    @endif
</div>
