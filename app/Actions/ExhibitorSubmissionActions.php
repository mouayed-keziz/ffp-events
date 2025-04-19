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
use Illuminate\Support\Facades\App;
use App\Notifications\Exhibitor\ExhibitorEventRegistrationAccepted;

class ExhibitorSubmissionActions
{

    public function getAcceptAction(): Action
    {
        return Action::make('accept')
            ->label(__('panel/exhibitor_submission.actions.accept'))
            ->color('success')
            ->icon('heroicon-o-check-circle')
            ->modalHeading(__('panel/exhibitor_submission.modals.accept'))
            ->modalDescription(__('panel/exhibitor_submission.modals.accept_description'))
            ->form([
                Forms\Components\Repeater::make('payment_slices')
                    ->collapsible()
                    ->label(__('panel/exhibitor_submission.sections.payment_slices'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label(__('panel/exhibitor_submission.fields.payment_slice.status'))
                                    ->options(PaymentSliceStatus::class)
                                    ->default(PaymentSliceStatus::NOT_PAYED)
                                    ->required()
                                    ->columnSpan(1)
                                    ->searchable(),
                                Forms\Components\TextInput::make('price')
                                    ->label(__('panel/exhibitor_submission.fields.payment_slice.price'))
                                    ->numeric()
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\Select::make('currency')
                                    ->label(__('panel/exhibitor_submission.fields.payment_slice.currency'))
                                    ->options(Currency::class)
                                    ->default(Currency::DA)
                                    ->required()
                                    ->columnSpan(1)
                                    ->searchable(),
                                Forms\Components\DatePicker::make('due_to')
                                    ->label(__('panel/exhibitor_submission.fields.payment_slice.due_to'))
                                    ->required()
                                    ->native(false)
                                    ->columnSpan(1)
                                    ->minDate(now()->toDateString())
                                    ->displayFormat('Y-m-d'),
                                SpatieMediaLibraryFileUpload::make('attachment')
                                    ->collection('attachement')
                                    ->label(__('panel/exhibitor_submission.fields.payment_slice.attachment'))
                                    ->downloadable()
                                    ->columnSpan(2)
                            ]),
                    ]),
            ])
            ->visible(fn(ExhibitorSubmission $record) => $record->status === ExhibitorSubmissionStatus::PENDING)
            ->action(function (array $data, ExhibitorSubmission $record): void {
                // Update submission status to accepted
                $record->status = ExhibitorSubmissionStatus::ACCEPTED;
                $record->save();

                // Log submission acceptance activity
                \App\Activity\ExhibitorSubmissionActivity::logSubmissionAccepted(auth()->user(), $record);

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

                // Send notification to exhibitor if associated with one
                if ($record->exhibitor) {
                    // Get the current locale for localized notification
                    $locale = App::getLocale();

                    try {
                        $record->exhibitor->notify(
                            new ExhibitorEventRegistrationAccepted(
                                $record->eventAnnouncement,
                                $record,
                                $locale
                            )
                        );

                        // Log notification sent
                        \Illuminate\Support\Facades\Log::info(
                            "Acceptance notification sent to exhibitor {$record->exhibitor->id} for submission {$record->id}"
                        );
                    } catch (\Exception $e) {
                        // Log error but continue
                        \Illuminate\Support\Facades\Log::error(
                            "Failed to send acceptance notification: " . $e->getMessage()
                        );
                    }
                }

                Notification::make()
                    ->title(__('panel/exhibitor_submission.success_messages.submission_accepted'))
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
            ->label(__('panel/exhibitor_submission.actions.reject'))
            ->color('danger')
            ->icon('heroicon-o-x-circle')
            ->requiresConfirmation()
            ->modalHeading(__('panel/exhibitor_submission.modals.reject'))
            ->modalDescription(__('panel/exhibitor_submission.modals.reject_description'))
            ->form([
                Forms\Components\Textarea::make('rejection_reason')
                    ->label(__('panel/exhibitor_submission.fields.rejection_reason_translatable'))
                    ->required()
                    ->rows(3)
                    ->translatable(),
            ])
            ->action(function (array $data, ExhibitorSubmission $record): void {
                // Update submission status to rejected and set rejection reason
                $record->status = ExhibitorSubmissionStatus::REJECTED;
                $record->rejection_reason = $data['rejection_reason'];
                $record->save();

                // Log submission rejection activity
                \App\Activity\ExhibitorSubmissionActivity::logSubmissionRejected(auth()->user(), $record, [
                    'rejection_reason' => $data['rejection_reason']
                ]);

                // Send notification to exhibitor if associated with one
                if ($record->exhibitor) {
                    // Get the current locale for localized notification
                    $locale = App::getLocale();

                    try {
                        $record->exhibitor->notify(
                            new \App\Notifications\Exhibitor\ExhibitorSubmissionRejected(
                                $record->eventAnnouncement,
                                $record,
                                $locale
                            )
                        );

                        // Log notification sent
                        \Illuminate\Support\Facades\Log::info(
                            "Rejection notification sent to exhibitor {$record->exhibitor->id} for submission {$record->id}"
                        );
                    } catch (\Exception $e) {
                        // Log error but continue
                        \Illuminate\Support\Facades\Log::error(
                            "Failed to send rejection notification: " . $e->getMessage()
                        );
                    }
                }

                Notification::make()
                    ->title(__('panel/exhibitor_submission.success_messages.submission_rejected'))
                    ->success()
                    ->send();
            });
    }

    public function getValidatePaymentAction(): TableAction
    {
        return TableAction::make('validatePayment')
            ->label(__('panel/exhibitor_submission.actions.validate'))
            ->color('success')
            ->icon('heroicon-o-check')
            ->requiresConfirmation()
            ->modalHeading(__('panel/exhibitor_submission.modals.validate_payment'))
            ->modalDescription(__('panel/exhibitor_submission.modals.validate_payment_description'))
            ->visible(fn(ExhibitorPaymentSlice $record) => $record->status === PaymentSliceStatus::PROOF_ATTACHED)
            ->action(function (ExhibitorPaymentSlice $record): void {
                $record->status = PaymentSliceStatus::VALID;
                $record->save();

                // Check if all payments are valid to update the submission status
                $submission = $record->exhibitorSubmission;

                // Log payment validation activity
                \App\Activity\ExhibitorSubmissionActivity::logPaymentValidated(auth()->user(), $submission, $record);

                $allSlices = $submission->paymentSlices;
                $allValid = $allSlices->every(fn($slice) => $slice->status === PaymentSliceStatus::VALID);

                if ($allValid) {
                    $submission->status = ExhibitorSubmissionStatus::FULLY_PAYED;
                    $submission->save();
                } else {
                    $submission->status = ExhibitorSubmissionStatus::PARTLY_PAYED;
                    $submission->save();
                }

                // Send notification to exhibitor if associated with one
                if ($submission->exhibitor) {
                    // Get the current locale for localized notification
                    $locale = App::getLocale();

                    try {
                        // Use different notification based on payment order
                        $isFirstPayment = $submission->paymentSlices()
                            ->where('status', PaymentSliceStatus::VALID)
                            ->count() === 1;

                        if ($isFirstPayment) {
                            $submission->exhibitor->notify(
                                new \App\Notifications\Exhibitor\ExhibitorPaymentRegistrationAccepted(
                                    $submission->eventAnnouncement,
                                    $record,
                                    $locale
                                )
                            );
                        } else {
                            $submission->exhibitor->notify(
                                new \App\Notifications\Exhibitor\ExhibitorSubsequentPaymentAccepted(
                                    $submission->eventAnnouncement,
                                    $record,
                                    $locale
                                )
                            );
                        }

                        // Log notification sent
                        \Illuminate\Support\Facades\Log::info(
                            "Payment validation notification sent to exhibitor {$submission->exhibitor->id} for submission {$submission->id}"
                        );
                    } catch (\Exception $e) {
                        // Log error but continue
                        \Illuminate\Support\Facades\Log::error(
                            "Failed to send payment validation notification: " . $e->getMessage()
                        );
                    }
                }

                Notification::make()
                    ->title(__('panel/exhibitor_submission.success_messages.payment_validated'))
                    ->success()
                    ->send();
            });
    }

    public function getRejectPaymentAction(): TableAction
    {
        return TableAction::make('rejectPayment')
            ->label(__('panel/exhibitor_submission.actions.reject'))
            ->color('danger')
            ->icon('heroicon-o-x-mark')
            ->requiresConfirmation()
            ->modalHeading(__('panel/exhibitor_submission.modals.reject_payment'))
            ->modalDescription(__('panel/exhibitor_submission.modals.reject_payment_description'))
            ->visible(fn(ExhibitorPaymentSlice $record) => $record->status === PaymentSliceStatus::PROOF_ATTACHED)
            ->action(function (ExhibitorPaymentSlice $record): void {
                $record->status = PaymentSliceStatus::NOT_PAYED;
                $record->save();

                // Update submission status if needed
                $submission = $record->exhibitorSubmission;

                // Log payment rejection activity
                \App\Activity\ExhibitorSubmissionActivity::logPaymentRejected(auth()->user(), $submission, $record);
                if ($submission->paymentSlices()->where('status', PaymentSliceStatus::VALID)->count() > 0) {
                    $submission->status = ExhibitorSubmissionStatus::PARTLY_PAYED;
                } else {
                    // If no valid payments, revert to accepted status
                    $submission->status = ExhibitorSubmissionStatus::ACCEPTED;
                }
                $submission->save();

                // Send notification to exhibitor if associated with one
                if ($submission->exhibitor) {
                    // Get the current locale for localized notification
                    $locale = App::getLocale();

                    try {
                        // Use different notification based on payment order
                        $isFirstPayment = $submission->paymentSlices()
                            ->where('status', PaymentSliceStatus::VALID)
                            ->count() === 0;

                        if ($isFirstPayment) {
                            $submission->exhibitor->notify(
                                new \App\Notifications\Exhibitor\ExhibitorPaymentRegistrationRejected(
                                    $submission->eventAnnouncement,
                                    $submission,
                                    $record,
                                    $locale
                                )
                            );
                        } else {
                            $submission->exhibitor->notify(
                                new \App\Notifications\Exhibitor\ExhibitorSubsequentPaymentRejected(
                                    $submission->eventAnnouncement,
                                    $record,
                                    $locale
                                )
                            );
                        }

                        // Log notification sent
                        \Illuminate\Support\Facades\Log::info(
                            "Payment rejection notification sent to exhibitor {$submission->exhibitor->id} for submission {$submission->id}"
                        );
                    } catch (\Exception $e) {
                        // Log error but continue
                        \Illuminate\Support\Facades\Log::error(
                            "Failed to send payment rejection notification: " . $e->getMessage()
                        );
                    }
                }

                Notification::make()
                    ->title(__('panel/exhibitor_submission.success_messages.payment_rejected'))
                    ->warning()
                    ->send();
            });
    }

    public function getDeletePaymentSliceAction(): TableAction
    {
        return TableAction::make('deletePaymentSlice')
            ->label(__('panel/exhibitor_submission.actions.delete'))
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
            ->modalHeading(__('panel/exhibitor_submission.modals.delete_payment_slice'))
            ->modalDescription(__('panel/exhibitor_submission.modals.delete_payment_slice_description'))
            ->action(function (ExhibitorPaymentSlice $record): void {
                // Get the submission before deleting the record
                $submission = $record->exhibitorSubmission;

                // Store the payment slice details for logging before it's deleted
                $paymentProperties = [
                    'payment_id' => $record->id,
                    'payment_amount' => $record->price,
                    'payment_currency' => $record->currency,
                    'due_date' => $record->due_to
                ];

                // Delete the record
                $record->delete();

                // Log payment slice deletion activity
                \App\Activity\ExhibitorSubmissionActivity::logPaymentSliceDeleted(auth()->user(), $submission, $paymentProperties);

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
                    ->title(__('panel/exhibitor_submission.success_messages.payment_slice_deleted'))
                    ->success()
                    ->send();
            });
    }

    public function getViewProofAction(): TableAction
    {
        return TableAction::make('viewProof')
            ->label(__('panel/exhibitor_submission.actions.view_proof'))
            ->color('info')
            // ->icon('heroicon-o-document-magnify-glass')
            ->url(fn(ExhibitorPaymentSlice $record) => $record->getFirstMediaUrl('attachement'), true)
            ->visible(fn(ExhibitorPaymentSlice $record) => $record->getFirstMedia('attachement') !== null);
    }

    public function getMakeReadyAction(): Action
    {
        return Action::make('makeReady')
            ->label(__('panel/exhibitor_submission.actions.mark_ready'))
            ->color('success')
            ->icon('heroicon-o-check-badge')
            ->requiresConfirmation()
            ->modalHeading(__('panel/exhibitor_submission.modals.make_ready'))
            ->modalDescription(__('panel/exhibitor_submission.modals.make_ready_description'))
            ->visible(fn(ExhibitorSubmission $record) => $record->status === ExhibitorSubmissionStatus::FULLY_PAYED)
            ->action(function (ExhibitorSubmission $record): void {
                $record->status = ExhibitorSubmissionStatus::READY;
                $record->save();

                // Log marking submission as ready
                \App\Activity\ExhibitorSubmissionActivity::logSubmissionMarkedReady(auth()->user(), $record);

                Notification::make()
                    ->title(__('panel/exhibitor_submission.success_messages.submission_ready'))
                    ->success()
                    ->send();
            });
    }

    public function getArchiveAction(): Action
    {
        return Action::make('archive')
            ->label(__('panel/exhibitor_submission.actions.archive'))
            ->color('gray')
            ->icon('heroicon-o-archive-box')
            ->requiresConfirmation()
            ->modalHeading(__('panel/exhibitor_submission.modals.archive'))
            ->modalDescription(__('panel/exhibitor_submission.modals.archive_description'))
            ->visible(fn(ExhibitorSubmission $record) => $record->status === ExhibitorSubmissionStatus::READY)
            ->action(function (ExhibitorSubmission $record): void {
                $record->status = ExhibitorSubmissionStatus::ARCHIVE;
                $record->save();

                // Log archiving submission
                \App\Activity\ExhibitorSubmissionActivity::logSubmissionArchived(auth()->user(), $record);

                Notification::make()
                    ->title(__('panel/exhibitor_submission.success_messages.submission_archived'))
                    ->success()
                    ->send();
            });
    }

    public function getApproveUpdateRequestAction(): Action
    {
        return Action::make('approveUpdateRequest')
            ->label(__('panel/exhibitor_submission.actions.approve_update'))
            ->color('success')
            ->outlined()
            ->icon('heroicon-o-check')
            ->requiresConfirmation()
            ->modalHeading(__('panel/exhibitor_submission.modals.approve_update'))
            ->modalDescription(__('panel/exhibitor_submission.modals.approve_update_description'))
            ->form([
                Forms\Components\DateTimePicker::make('edit_deadline')
                    ->label(__('panel/exhibitor_submission.details.edit_deadline'))
                    ->required()
                    ->native(false)
                    ->minDate(now())
            ])
            ->visible(fn(ExhibitorSubmission $record) => $record->update_requested_at !== null)
            ->action(function (array $data, ExhibitorSubmission $record): void {
                $record->edit_deadline = $data['edit_deadline'];
                $record->update_requested_at = null;
                $record->save();

                // Log update request approval
                \App\Activity\ExhibitorSubmissionActivity::logUpdateRequestApproved(auth()->user(), $record, [
                    'edit_deadline' => $data['edit_deadline']
                ]);

                // Send notification to exhibitor if associated with one
                if ($record->exhibitor) {
                    // Get the current locale for localized notification
                    $locale = App::getLocale();

                    try {
                        $record->exhibitor->notify(
                            new \App\Notifications\Exhibitor\ExhibitorEditSubmissionRequestAccepted(
                                $record->eventAnnouncement,
                                $record,
                                $locale,
                                $data['edit_deadline']
                            )
                        );

                        // Log notification sent
                        \Illuminate\Support\Facades\Log::info(
                            "Edit submission request accepted notification sent to exhibitor {$record->exhibitor->id} for submission {$record->id}"
                        );
                    } catch (\Exception $e) {
                        // Log error but continue
                        \Illuminate\Support\Facades\Log::error(
                            "Failed to send edit submission request accepted notification: " . $e->getMessage()
                        );
                    }
                }

                Notification::make()
                    ->title(__('panel/exhibitor_submission.success_messages.update_request_approved'))
                    ->success()
                    ->send();
            });
    }

    public function getDenyUpdateRequestAction(): Action
    {
        return Action::make('denyUpdateRequest')
            ->label(__('panel/exhibitor_submission.actions.deny_update'))
            ->color('danger')
            ->outlined()
            ->icon('heroicon-o-x-mark')
            ->requiresConfirmation()
            ->modalHeading(__('panel/exhibitor_submission.modals.deny_update'))
            ->modalDescription(__('panel/exhibitor_submission.modals.deny_update_description'))
            ->visible(fn(ExhibitorSubmission $record) => $record->update_requested_at !== null)
            ->action(function (ExhibitorSubmission $record): void {
                $record->update_requested_at = null;
                $record->save();

                // Send notification to exhibitor if associated with one
                if ($record->exhibitor) {
                    // Get the current locale for localized notification
                    $locale = App::getLocale();

                    try {
                        $record->exhibitor->notify(
                            new \App\Notifications\Exhibitor\ExhibitorEditSubmissionRequestRejected(
                                $record->eventAnnouncement,
                                $record,
                                $locale
                            )
                        );

                        // Log notification sent
                        \Illuminate\Support\Facades\Log::info(
                            "Edit submission request rejected notification sent to exhibitor {$record->exhibitor->id} for submission {$record->id}"
                        );
                    } catch (\Exception $e) {
                        // Log error but continue
                        \Illuminate\Support\Facades\Log::error(
                            "Failed to send edit submission request rejected notification: " . $e->getMessage()
                        );
                    }
                }

                Notification::make()
                    ->title(__('panel/exhibitor_submission.success_messages.update_request_denied'))
                    ->warning()
                    ->send();
            });
    }

    public function getDownloadInvoiceAction(): Action
    {
        return Action::make('downloadInvoice')
            ->label(__('panel/exhibitor_submission.actions.download_invoice'))
            ->icon('heroicon-o-document-arrow-down')
            ->color('primary')
            ->url(function (ExhibitorSubmission $record) {
                return route('admin.exhibitor_submissions.download_invoice', ['record' => $record->id]);
            }, shouldOpenInNewTab: true)
            ->visible(function (ExhibitorSubmission $record) {
                return $record->canDownloadInvoice;
            });
    }
}
