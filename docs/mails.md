# Application Mails (Mailables)

This document describes the Mailable classes used in the Laravel application. Mailables are responsible for building and sending emails. They often work in conjunction with Notification classes but can also be used directly.

Many Mailable classes use the `Queueable` and `SerializesModels` traits, allowing them to be queued for asynchronous sending. The locale for the email content is often determined in the constructor, either by a passed parameter, a fixed value (for admin emails) is set (fr), or the application's current locale (`App::getLocale()`). The text direction (`ltr` or `rtl`) is also typically set based on the locale.

---

## Admin Mailables

These Mailables are used to send emails to administrators or the central company email address.

### `App\Mail\Admin\ExhibitorModificationRequestMail`
*   **`App\Notifications\Admin\ExhibitorModificationRequest` notification.)**
*   **Purpose:** Likely informs admins about an exhibitor's request to modify their submission.
*   **Key Data (Expected):** `EventAnnouncement`, `Exhibitor`, `ExhibitorSubmission`, `locale` (likely 'fr' based on notification patterns).
*   **View (Expected):** A Blade view, typically located under `resources/views/mails/admin/`.
*   **Associated Notification:** `App\Notifications\Admin\ExhibitorModificationRequest`

### `App\Mail\Admin\ExhibitorPaymentProofMail`
*   **Class:** `App\Mail\Admin\ExhibitorPaymentProofMail`
*   **Purpose:** Sends an email to admins when an exhibitor uploads proof of payment.
*   **Key Data:** `event` (EventAnnouncement), `exhibitor` (Exhibitor), `submission` (ExhibitorSubmission), `payment` (ExhibitorPaymentSlice), `admin` (the notifiable admin User), `locale`, `direction`. (Locale is likely 'fr' as per the associated notification's pattern).
*   **View:** Determined by the `content()` method, typically `mails.admin.exhibitor-payment-proof`.
*   **Associated Notification:** `App\Notifications\Admin\ExhibitorPaymentProof`

### `App\Mail\Admin\ExhibitorPostSubmissionMail`
*   **Class:** `App\Mail\Admin\ExhibitorPostSubmissionMail`
*   **Purpose:** Informs admins when an exhibitor completes a post-submission step.
*   **Key Data:** `event` (EventAnnouncement), `exhibitor` (Exhibitor), `submission` (ExhibitorSubmission), `admin` (the notifiable admin User), `locale` (hardcoded to 'fr'), `direction`.
*   **View:** Determined by the `content()` method, typically `mails.admin.exhibitor-post-submission`.
*   **Associated Notification:** `App\Notifications\Admin\ExhibitorPostSubmission`

### `App\Mail\Admin\ExhibitorSubmissionUpdateMail`
*   **Class:** `App\Mail\Admin\ExhibitorSubmissionUpdateMail`
*   **Purpose:** Notifies admins about an update to an exhibitor's submission.
*   **Key Data:** `event` (EventAnnouncement), `exhibitor` (Exhibitor), `submission` (ExhibitorSubmission), `admin` (the notifiable admin User), `locale` (hardcoded to 'fr'), `direction`.
*   **View:** Determined by the `content()` method, typically `mails.admin.exhibitor-submission-update`.
*   **Associated Notification:** `App\Notifications\Admin\ExhibitorSubmissionUpdate`

### `App\Mail\Admin\ExhibitorUpdatedBadgesMail`
*   **Class:** `App\Mail\Admin\ExhibitorUpdatedBadgesMail`
*   **Purpose:** Alerts admins when an exhibitor has updated their badge information.
*   **Key Data:** `event` (EventAnnouncement), `exhibitor` (Exhibitor), `submission` (ExhibitorSubmission), `badges` (Collection), `admin` (the notifiable admin User), `locale`, `direction`. (Locale is likely 'fr' as per the associated notification's pattern).
*   **View:** Determined by the `content()` method, typically `mails.admin.exhibitor-updated-badges`.
*   **Associated Notification:** `App\Notifications\Admin\ExhibitorUpdatedBadges`

### `App\Mail\Admin\NewExhibitorSubmissionMail`
*   **Class:** `App\Mail\Admin\NewExhibitorSubmissionMail`
*   **Purpose:** Notifies admins about a new exhibitor submission.
*   **Key Data:** `event` (EventAnnouncement), `exhibitor` (Exhibitor), `submission` (ExhibitorSubmission), `admin` (the notifiable admin User), `locale` (hardcoded to 'fr'), `direction`.
*   **View:** Determined by the `content()` method, typically `mails.admin.new-exhibitor-submission`.
*   **Associated Notification:** `App\Notifications\Admin\NewExhibitorSubmission`

---

## Exhibitor Mailables

These Mailables are used to send emails directly to exhibitors.

### `App\Mail\Exhibitor\ExhibitorEditSubmissionRequestAcceptedMail`
*   **Class:** `App\Mail\Exhibitor\ExhibitorEditSubmissionRequestAcceptedMail`
*   **Purpose:** Informs an exhibitor that their request to edit their submission has been accepted.
*   **Key Data:** `event` (EventAnnouncement), `exhibitor` (Exhibitor), `submission` (ExhibitorSubmission), `edit_deadline`, `locale`, `direction`.
*   **View:** `mails.exhibitor.exhibitor-edit-submission-request-accepted`
*   **Associated Notification:** `App\Notifications\Exhibitor\ExhibitorEditSubmissionRequestAccepted`

### `App\Mail\Exhibitor\ExhibitorEditSubmissionRequestRejectedMail`
*   **Class:** `App\Mail\Exhibitor\ExhibitorEditSubmissionRequestRejectedMail`
*   **Purpose:** Informs an exhibitor that their request to edit their submission has been rejected.
*   **Key Data:** `event` (EventAnnouncement), `exhibitor` (Exhibitor), `submission` (ExhibitorSubmission), `locale`, `direction`.
*   **View:** `mails.exhibitor.exhibitor-edit-submission-request-rejected`
*   **Associated Notification:** `App\Notifications\Exhibitor\ExhibitorEditSubmissionRequestRejected`

### `App\Mail\Exhibitor\ExhibitorEventRegistrationAcceptedMail`
*   **Class:** `App\Mail\Exhibitor\ExhibitorEventRegistrationAcceptedMail`
*   **Purpose:** Notifies an exhibitor that their event registration has been accepted.
*   **Key Data:** `event` (EventAnnouncement), `exhibitor` (Exhibitor), `submission` (ExhibitorSubmission), `locale`, `direction`.
*   **View:** `mails.exhibitor.exhibitor-event-registration-accepted`
*   **Subject:** Dynamically set using `__('emails/exhibitor-event-registration-accepted.subject', ['event_name' => ...])`
*   **Associated Notification:** `App\Notifications\Exhibitor\ExhibitorEventRegistrationAccepted`

### `App\Mail\Exhibitor\ExhibitorGeneratedBadgesMail`
*   **Class:** `App\Mail\Exhibitor\ExhibitorGeneratedBadgesMail`
*   **Purpose:** Informs an exhibitor that their event badges have been generated and sends them as attachments.
*   **Key Data:** `event` (EventAnnouncement), `exhibitor` (Exhibitor), `submission` (ExhibitorSubmission), `badges` (Collection), `locale`, `direction`.
*   **View:** Determined by the `content()` method, typically `mails.exhibitor.exhibitor-generated-badges`.
*   **Attachments:** Yes, badge files (potentially zipped, as indicated by `ZipArchive` usage in the constructor).
*   **Associated Notification:** `App\Notifications\Exhibitor\ExhibitorGeneratedBadges`

### `App\Mail\Exhibitor\ExhibitorPaymentRegistrationAcceptedMail`
*   **Class:** `App\Mail\Exhibitor\ExhibitorPaymentRegistrationAcceptedMail`
*   **Purpose:** Confirms to an exhibitor that their payment has been accepted.
*   **Key Data:** `event` (EventAnnouncement), `exhibitor` (Exhibitor), `paymentSlice` (ExhibitorPaymentSlice), `locale`, `direction`.
*   **View:** Determined by the `content()` method, typically `mails.exhibitor.exhibitor-payment-registration-accepted`.
*   **Associated Notification:** `App\Notifications\Exhibitor\ExhibitorPaymentRegistrationAccepted`

### `App\Mail\Exhibitor\ExhibitorPaymentRegistrationRejectedMail`
*   **Class:** `App\Mail\Exhibitor\ExhibitorPaymentRegistrationRejectedMail`
*   **Purpose:** Informs an exhibitor that their payment has been rejected.
*   **Key Data:** `event` (EventAnnouncement), `exhibitor` (Exhibitor), `submission` (ExhibitorSubmission), `paymentSlice` (ExhibitorPaymentSlice), `locale`, `direction`.
*   **View:** Determined by the `content()` method, typically `mails.exhibitor.exhibitor-payment-registration-rejected`.
*   **Associated Notification:** `App\Notifications\Exhibitor\ExhibitorPaymentRegistrationRejected`

### `App\Mail\Exhibitor\ExhibitorSubmissionProcessingMail`
*   **Class:** `App\Mail\Exhibitor\ExhibitorSubmissionProcessingMail`
*   **Purpose:** Notifies an exhibitor that their submission is being processed.
*   **Key Data:** `event` (EventAnnouncement), `exhibitor` (Exhibitor), `submission` (ExhibitorSubmission), `locale`, `direction`.
*   **View:** Determined by the `content()` method, typically `mails.exhibitor.exhibitor-submission-processing`.
*   **Associated Notification:** `App\Notifications\Exhibitor\ExhibitorSubmissionProcessing`

### `App\Mail\Exhibitor\ExhibitorSubmissionRejectedMail`
*   **Class:** `App\Mail\Exhibitor\ExhibitorSubmissionRejectedMail`
*   **Purpose:** Informs an exhibitor that their submission has been rejected.
*   **Key Data:** `event` (EventAnnouncement), `exhibitor` (Exhibitor), `submission` (ExhibitorSubmission), `locale`, `direction`.
*   **View:** Determined by the `content()` method, typically `mails.exhibitor.exhibitor-submission-rejected`.
*   **Associated Notification:** `App\Notifications\Exhibitor\ExhibitorSubmissionRejected`

### `App\Mail\Exhibitor\ExhibitorSubsequentPaymentAcceptedMail`
*   **Class:** `App\Mail\Exhibitor\ExhibitorSubsequentPaymentAcceptedMail`
*   **Purpose:** Notifies an exhibitor that a subsequent payment has been accepted.
*   **Key Data:** `event` (EventAnnouncement), `exhibitor` (Exhibitor), `paymentSlice` (ExhibitorPaymentSlice), `locale`, `direction`.
*   **View:** Determined by the `content()` method, typically `mails.exhibitor.exhibitor-subsequent-payment-accepted`.
*   **Associated Notification:** `App\Notifications\Exhibitor\ExhibitorSubsequentPaymentAccepted`

### `App\Mail\Exhibitor\ExhibitorSubsequentPaymentRejectedMail`
*   **Class:** `App\Mail\Exhibitor\ExhibitorSubsequentPaymentRejectedMail`
*   **Purpose:** Informs an exhibitor that a subsequent payment has been rejected.
*   **Key Data:** `event` (EventAnnouncement), `exhibitor` (Exhibitor), `paymentSlice` (ExhibitorPaymentSlice), `locale`, `direction`.
*   **View:** Determined by the `content()` method, typically `mails.exhibitor.exhibitor-subsequent-payment-rejected`.
*   **Associated Notification:** `App\Notifications\Exhibitor\ExhibitorSubsequentPaymentRejected`

---

## Visitor Mailables

These Mailables are used to send emails directly to event visitors.

### `App\Mail\Visitor\VisitorEventRegistrationSuccessfulMail`
*   **Class:** `App\Mail\Visitor\VisitorEventRegistrationSuccessfulMail`
*   **Purpose:** Confirms successful event registration to a visitor.
*   **Key Data:** `event` (EventAnnouncement), `visitor` (Visitor), `submission` (VisitorSubmission), `locale`, `direction`.
*   **View:** Determined by the `content()` method, typically `mails.visitor.visitor-event-registration-successful`.
*   **Attachments:** Details in the `attachments()` method (if any).
*   **Associated Notification:** `App\Notifications\Visitor\VisitorEventRegistrationSuccessful`

---

## General Mailables

These Mailables are used for general purposes like account management and are not tied to a specific user role's workflow.

### `App\Mail\ChangeEmailMail`
*   **Class:** `App\Mail\ChangeEmailMail`
*   **Purpose:** Sends an email for verifying a new email address when a user (any type: User, Exhibitor, Visitor) requests an email change. Contains a verification token/link.
*   **Key Data:** `token`, `model` (the user model instance), `locale`, `name` (type of user model), `direction`.
*   **View:** Determined by the `content()` method, likely a generic email verification template.

### `App\Mail\ResetPasswordMail`
*   **Class:** `App\Mail\ResetPasswordMail`
*   **Purpose:** Sends a password reset link (token) to a user (any type).
*   **Key Data:** `token`, `model` (the user model instance), `locale`, `name` (type of user model), `direction`.
*   **View:** Determined by the `content()` method, likely a generic password reset template.
