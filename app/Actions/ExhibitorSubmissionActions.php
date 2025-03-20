<?php

namespace App\Actions;

use App\Enums\ExhibitorSubmissionStatus;
use App\Enums\PaymentSliceStatus;
use App\Enums\Currency;
use App\Models\ExhibitorPaymentSlice;
use App\Models\ExhibitorSubmission;
use Filament\Actions\Action;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class ExhibitorSubmissionActions
{

    public function getAcceptAction(): Action
    {
        return Action::make('accept')
            ->label(__('Accept'))
            ->color('success')
            ->icon('heroicon-o-check-circle')
            ->modalHeading(__('Accept Submission'))
            ->modalDescription(__('This will accept the exhibitor submission and create payment slices.'))
            ->form([
                Forms\Components\Repeater::make('payment_slices')
                    ->collapsible()
                    ->label(__('Payment Slices'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label(__('exhibitor_submission.fields.payment_slice.status'))
                                    ->options(PaymentSliceStatus::class)
                                    ->default(PaymentSliceStatus::NOT_PAYED)
                                    ->required()
                                    ->columnSpan(1)
                                    ->searchable(),
                                Forms\Components\TextInput::make('price')
                                    ->label(__('exhibitor_submission.fields.payment_slice.price'))
                                    ->numeric()
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\Select::make('currency')
                                    ->label(__('exhibitor_submission.fields.payment_slice.currency'))
                                    ->options(Currency::class)
                                    ->default(Currency::DA)
                                    ->required()
                                    ->columnSpan(1)
                                    ->searchable(),
                                Forms\Components\DatePicker::make('due_to')
                                    ->label(__('exhibitor_submission.fields.payment_slice.due_to'))
                                    ->required()
                                    ->native(false)
                                    ->columnSpan(1)
                                    ->minDate(now()->toDateString())
                                    ->displayFormat('Y-m-d'),
                                SpatieMediaLibraryFileUpload::make('attachment')
                                    ->collection('attachement')
                                    ->label(__('exhibitor_submission.fields.payment_slice.attachment'))
                                    ->downloadable()
                                    ->columnSpan(2)
                            ]),
                    ])
                    ->required()
                    ->minItems(1)
                    ->defaultItems(1),
            ])
            ->visible(fn(ExhibitorSubmission $record) => $record->status === ExhibitorSubmissionStatus::PENDING)
            ->action(function (array $data, ExhibitorSubmission $record): void {
                // Update submission status to accepted
                $record->status = ExhibitorSubmissionStatus::ACCEPTED;
                $record->save();

                // Create payment slices
                $sort = 1;
                foreach ($data['payment_slices'] as $sliceData) {
                    $slice = $record->paymentSlices()->create([
                        'sort' => $sort,
                        'price' => $sliceData['price'],
                        'status' => $sliceData['status'],
                        'currency' => $sliceData['currency'],
                        'due_to' => $sliceData['due_to'],
                    ]);

                    // Handle attachment if provided
                    if (isset($sliceData['attachment']) && $sliceData['attachment']) {
                        $slice->attachMedia($sliceData['attachment']);
                    }

                    $sort++;
                }

                Notification::make()
                    ->title(__('Submission accepted successfully'))
                    ->success()
                    ->send();
            });
    }

    public function getRejectAction(): Action
    {
        return Action::make('reject')
            ->visible(
                fn(ExhibitorSubmission $record) =>
                $record->status !== ExhibitorSubmissionStatus::REJECTED &&
                    $record->status !== ExhibitorSubmissionStatus::READY &&
                    $record->status !== ExhibitorSubmissionStatus::ARCHIVE
            )
            ->label(__('Reject'))
            ->color('danger')
            ->icon('heroicon-o-x-circle')
            ->requiresConfirmation()
            ->modalHeading(__('Reject Submission'))
            ->modalDescription(__('Are you sure you want to reject this exhibitor submission?'))
            ->action(function (ExhibitorSubmission $record): void {
                $record->status = ExhibitorSubmissionStatus::REJECTED;
                $record->save();

                Notification::make()
                    ->title(__('Submission rejected'))
                    ->success()
                    ->send();
            });
    }

    public function getValidatePaymentAction(): TableAction
    {
        return TableAction::make('validatePayment')
            ->label(__('Validate'))
            ->color('success')
            ->icon('heroicon-o-check')
            ->requiresConfirmation()
            ->modalHeading(__('Validate Payment'))
            ->modalDescription(__('Are you sure you want to validate this payment proof?'))
            ->visible(fn(ExhibitorPaymentSlice $record) => $record->status === PaymentSliceStatus::PROOF_ATTACHED)
            ->action(function (ExhibitorPaymentSlice $record): void {
                $record->status = PaymentSliceStatus::VALID;
                $record->save();

                // Check if all payments are valid to update the submission status
                $submission = $record->exhibitorSubmission;
                $allSlices = $submission->paymentSlices;
                $allValid = $allSlices->every(fn($slice) => $slice->status === PaymentSliceStatus::VALID);

                if ($allValid) {
                    $submission->status = ExhibitorSubmissionStatus::FULLY_PAYED;
                    $submission->save();
                } else {
                    $submission->status = ExhibitorSubmissionStatus::PARTLY_PAYED;
                    $submission->save();
                }

                Notification::make()
                    ->title(__('Payment validated successfully'))
                    ->success()
                    ->send();
            });
    }

    public function getRejectPaymentAction(): TableAction
    {
        return TableAction::make('rejectPayment')
            ->label(__('Reject'))
            ->color('danger')
            ->icon('heroicon-o-x-mark')
            ->requiresConfirmation()
            ->modalHeading(__('Reject Payment'))
            ->modalDescription(__('Are you sure you want to reject this payment proof?'))
            ->visible(fn(ExhibitorPaymentSlice $record) => $record->status === PaymentSliceStatus::PROOF_ATTACHED)
            ->action(function (ExhibitorPaymentSlice $record): void {
                $record->status = PaymentSliceStatus::NOT_PAYED;
                $record->save();

                // Update submission status if needed
                $submission = $record->exhibitorSubmission;
                if ($submission->paymentSlices()->where('status', PaymentSliceStatus::VALID)->count() > 0) {
                    $submission->status = ExhibitorSubmissionStatus::PARTLY_PAYED;
                } else {
                    // If no valid payments, revert to accepted status
                    $submission->status = ExhibitorSubmissionStatus::ACCEPTED;
                }
                $submission->save();

                Notification::make()
                    ->title(__('Payment proof rejected'))
                    ->warning()
                    ->send();
            });
    }

    public function getDeletePaymentSliceAction(): TableAction
    {
        return TableAction::make('deletePaymentSlice')
            ->label(__('Delete'))
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
            ->modalHeading(__('Delete Payment Slice'))
            ->modalDescription(__('Are you sure you want to delete this payment slice? This cannot be undone.'))
            ->action(function (ExhibitorPaymentSlice $record): void {
                // Get the submission before deleting the record
                $submission = $record->exhibitorSubmission;

                // Delete the record
                $record->delete();

                // Check remaining payment slices to update submission status
                $remainingSlices = $submission->paymentSlices;

                if ($remainingSlices->isEmpty()) {
                    // No payment slices left, revert to ACCEPTED status
                    $submission->status = ExhibitorSubmissionStatus::ACCEPTED;
                } else if ($remainingSlices->where('status', PaymentSliceStatus::VALID)->count() > 0) {
                    // At least one valid payment exists
                    if ($remainingSlices->every(fn($slice) => $slice->status === PaymentSliceStatus::VALID)) {
                        // All remaining slices are valid
                        $submission->status = ExhibitorSubmissionStatus::FULLY_PAYED;
                    } else {
                        // Some valid payments but not all
                        $submission->status = ExhibitorSubmissionStatus::PARTLY_PAYED;
                    }
                } else {
                    // No valid payments left
                    $submission->status = ExhibitorSubmissionStatus::ACCEPTED;
                }

                $submission->save();

                Notification::make()
                    ->title(__('Payment slice deleted successfully'))
                    ->success()
                    ->send();
            });
    }

    public function getViewProofAction(): TableAction
    {
        return TableAction::make('viewProof')
            ->label(__('View Proof'))
            ->color('info')
            // ->icon('heroicon-o-document-magnify-glass')
            ->url(fn(ExhibitorPaymentSlice $record) => $record->getFirstMediaUrl('attachement'), true)
            ->visible(fn(ExhibitorPaymentSlice $record) => $record->getFirstMedia('attachement') !== null);
    }

    public function getMakeReadyAction(): Action
    {
        return Action::make('makeReady')
            ->label(__('Mark as Ready'))
            ->color('success')
            ->icon('heroicon-o-check-badge')
            ->requiresConfirmation()
            ->modalHeading(__('Mark Submission as Ready'))
            ->modalDescription(__('This will mark the exhibitor submission as ready. Are you sure you want to continue?'))
            ->visible(fn(ExhibitorSubmission $record) => $record->status === ExhibitorSubmissionStatus::FULLY_PAYED)
            ->action(function (ExhibitorSubmission $record): void {
                $record->status = ExhibitorSubmissionStatus::READY;
                $record->save();

                Notification::make()
                    ->title(__('Submission marked as ready'))
                    ->success()
                    ->send();
            });
    }

    public function getArchiveAction(): Action
    {
        return Action::make('archive')
            ->label(__('Archive'))
            ->color('gray')
            ->icon('heroicon-o-archive-box')
            ->requiresConfirmation()
            ->modalHeading(__('Archive Submission'))
            ->modalDescription(__('This will archive the exhibitor submission. Are you sure you want to continue?'))
            ->visible(fn(ExhibitorSubmission $record) => $record->status === ExhibitorSubmissionStatus::READY)
            ->action(function (ExhibitorSubmission $record): void {
                $record->status = ExhibitorSubmissionStatus::ARCHIVE;
                $record->save();

                Notification::make()
                    ->title(__('Submission archived successfully'))
                    ->success()
                    ->send();
            });
    }
}
