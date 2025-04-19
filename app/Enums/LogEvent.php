<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum LogEvent: string implements HasLabel, HasColor, HasIcon
{
    case Creation = 'creation';
    case Modification = 'modification';
    case Deletion = 'deletion';

    case ForceDeletion = 'force_deletion';
    case Restoration = 'restoration';

    case Login = 'login';
    case Logout = 'Logout';
    case Register = 'register';

        // Visitor events
    case VisitorSubmitted = 'visitor_submitted';

        // Exhibitor events
    case ExhibitorSubmitted = 'exhibitor_submitted';
    case ExhibitorUploadedPaymentProof = 'exhibitor_uploaded_payment_proof';
    case ExhibitorSubmittedPostForms = 'exhibitor_submitted_post_forms';
    case ExhibitorRequestedUpdate = 'exhibitor_requested_update';
    case ExhibitorUpdatedSubmission = 'exhibitor_updated_submission';
    case ExhibitorDownloadedInvoice = 'exhibitor_downloaded_invoice';

        // Admin actions on exhibitor submissions
    case ExhibitorSubmissionAccepted = 'exhibitor_submission_accepted';
    case ExhibitorSubmissionRejected = 'exhibitor_submission_rejected';
    case ExhibitorPaymentValidated = 'exhibitor_payment_validated';
    case ExhibitorPaymentRejected = 'exhibitor_payment_rejected';
    case ExhibitorPaymentSliceDeleted = 'exhibitor_payment_slice_deleted';
    case ExhibitorSubmissionMarkedReady = 'exhibitor_submission_marked_ready';
    case ExhibitorSubmissionArchived = 'exhibitor_submission_archived';
    case ExhibitorUpdateRequestApproved = 'exhibitor_update_request_approved';
    case ExhibitorUpdateRequestDenied = 'exhibitor_update_request_denied';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Creation => __('panel/logs.events.creation'),
            self::Modification => __('panel/logs.events.modification'),
            self::Deletion => __('panel/logs.events.deletion'),

            self::ForceDeletion => __('panel/logs.events.force_deletion'),
            self::Restoration => __('panel/logs.events.restoration'),

            self::Login => __('panel/logs.events.login'),
            self::Logout => __('panel/logs.events.logout'),
            self::Register => __('panel/logs.events.register'),

            // Visitor events
            self::VisitorSubmitted => __('panel/logs.events.visitor_submitted'),

            // Exhibitor events
            self::ExhibitorSubmitted => __('panel/logs.events.exhibitor_submitted'),
            self::ExhibitorUploadedPaymentProof => __('panel/logs.events.exhibitor_uploaded_payment_proof'),
            self::ExhibitorSubmittedPostForms => __('panel/logs.events.exhibitor_submitted_post_forms'),
            self::ExhibitorRequestedUpdate => __('panel/logs.events.exhibitor_requested_update'),
            self::ExhibitorUpdatedSubmission => __('panel/logs.events.exhibitor_updated_submission'),
            self::ExhibitorDownloadedInvoice => __('panel/logs.events.exhibitor_downloaded_invoice'),

            // Admin actions on exhibitor submissions
            self::ExhibitorSubmissionAccepted => __('panel/logs.events.exhibitor_submission_accepted'),
            self::ExhibitorSubmissionRejected => __('panel/logs.events.exhibitor_submission_rejected'),
            self::ExhibitorPaymentValidated => __('panel/logs.events.exhibitor_payment_validated'),
            self::ExhibitorPaymentRejected => __('panel/logs.events.exhibitor_payment_rejected'),
            self::ExhibitorPaymentSliceDeleted => __('panel/logs.events.exhibitor_payment_slice_deleted'),
            self::ExhibitorSubmissionMarkedReady => __('panel/logs.events.exhibitor_submission_marked_ready'),
            self::ExhibitorSubmissionArchived => __('panel/logs.events.exhibitor_submission_archived'),
            self::ExhibitorUpdateRequestApproved => __('panel/logs.events.exhibitor_update_request_approved'),
            self::ExhibitorUpdateRequestDenied => __('panel/logs.events.exhibitor_update_request_denied'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Creation => 'success',
            self::Modification => 'warning',
            self::Deletion => 'danger',

            self::ForceDeletion => 'danger',
            self::Restoration => 'gray',

            self::Login => 'info',
            self::Logout => 'info',
            self::Register => 'success',

            // Visitor events
            self::VisitorSubmitted => 'success',

            // Exhibitor events
            self::ExhibitorSubmitted => 'success',
            self::ExhibitorUploadedPaymentProof => 'info',
            self::ExhibitorSubmittedPostForms => 'success',
            self::ExhibitorRequestedUpdate => 'warning',
            self::ExhibitorUpdatedSubmission => 'warning',
            self::ExhibitorDownloadedInvoice => 'info',

            // Admin actions on exhibitor submissions
            self::ExhibitorSubmissionAccepted => 'success',
            self::ExhibitorSubmissionRejected => 'danger',
            self::ExhibitorPaymentValidated => 'success',
            self::ExhibitorPaymentRejected => 'danger',
            self::ExhibitorPaymentSliceDeleted => 'danger',
            self::ExhibitorSubmissionMarkedReady => 'info',
            self::ExhibitorSubmissionArchived => 'gray',
            self::ExhibitorUpdateRequestApproved => 'success',
            self::ExhibitorUpdateRequestDenied => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Creation => 'heroicon-o-plus',
            self::Modification => 'heroicon-o-pencil-square',
            self::Deletion => 'heroicon-o-trash',

            self::ForceDeletion => 'heroicon-o-trash',
            self::Restoration => 'heroicon-o-arrow-path',

            self::Login => 'heroicon-o-arrow-left-start-on-rectangle',
            self::Logout => 'heroicon-o-arrow-left-end-on-rectangle',
            self::Register => 'heroicon-o-user-plus',

            // Visitor events
            self::VisitorSubmitted => 'heroicon-o-clipboard-document-check',

            // Exhibitor events
            self::ExhibitorSubmitted => 'heroicon-o-document-check',
            self::ExhibitorUploadedPaymentProof => 'heroicon-o-banknotes',
            self::ExhibitorSubmittedPostForms => 'heroicon-o-document-text',
            self::ExhibitorRequestedUpdate => 'heroicon-o-pencil',
            self::ExhibitorUpdatedSubmission => 'heroicon-o-document-plus',
            self::ExhibitorDownloadedInvoice => 'heroicon-o-document-arrow-down',

            // Admin actions on exhibitor submissions
            self::ExhibitorSubmissionAccepted => 'heroicon-o-check-circle',
            self::ExhibitorSubmissionRejected => 'heroicon-o-x-circle',
            self::ExhibitorPaymentValidated => 'heroicon-o-check-badge',
            self::ExhibitorPaymentRejected => 'heroicon-o-x-mark',
            self::ExhibitorPaymentSliceDeleted => 'heroicon-o-trash',
            self::ExhibitorSubmissionMarkedReady => 'heroicon-o-flag',
            self::ExhibitorSubmissionArchived => 'heroicon-o-archive-box',
            self::ExhibitorUpdateRequestApproved => 'heroicon-o-check',
            self::ExhibitorUpdateRequestDenied => 'heroicon-o-no-symbol',
        };
    }
}
