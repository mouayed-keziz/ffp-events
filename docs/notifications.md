# Application Notifications

This document outlines the notification system within the Laravel application. Notifications are used to inform users (Administrators, Exhibitors, and Visitors) about important events and actions.

## Notification Delivery Channels

The application utilizes different notification channels depending on the recipient:

*   **For Visitors and Exhibitors:**
    *   **Email:** Notifications are sent directly to their registered email addresses.
    *   **In-App (Database):** Notifications are stored in the database and can be displayed within the user-facing sections of the application.

*   **For Administrators and Super Admins (Users):**
    *   **Filament Notifications:** These are custom database-driven notifications integrated into the Filament admin panel, providing real-time alerts.
    *   **Email to Company:** Critical notifications are also sent to a central company email address, which is configured in the application settings (via Spatie Laravel Settings).

Many notifications are queued for background processing to ensure a responsive user experience, indicated by the `ShouldQueue` interface.

## Notification List

Below is a breakdown of the different notification classes used in the application, categorized by their primary audience.

---

### Admin Notifications

These notifications are primarily intended for system administrators and super admins. They are typically delivered via Filament notifications and email to the company's central address. Most admin notifications are hardcoded to use the French (`fr`) locale for the email content sent to the company.

*   **`App\Notifications\Admin\ExhibitorModificationRequest`**
    *   **Purpose:** Notifies admins when an exhibitor requests to make modifications to their submitted application/details.
    *   **Queued:** Yes

*   **`App\Notifications\Admin\ExhibitorPaymentProof`**
    *   **Purpose:** Alerts admins when an exhibitor has uploaded proof of payment.
    *   **Queued:** Yes

*   **`App\Notifications\Admin\ExhibitorPostSubmission`**
    *   **Purpose:** Informs admins when an exhibitor completes a post-submission step (e.g., filling out forms that become available after their initial submission is approved or payment is made).
    *   **Queued:** Yes

*   **`App\Notifications\Admin\ExhibitorSubmissionUpdate`**
    *   **Purpose:** Notifies admins when there is an update to an existing exhibitor's submission.
    *   **Queued:** Yes

*   **`App\Notifications\Admin\ExhibitorUpdatedBadges`**
    *   **Purpose:** Alerts admins when an exhibitor has updated their badge information.
    *   **Queued:** Yes

*   **`App\Notifications\Admin\NewExhibitorSubmission`**
    *   **Purpose:** Notifies admins immediately when a new exhibitor submits their application for an event.
    *   **Queued:** Yes

---

### Exhibitor Notifications

These notifications are sent to exhibitors regarding their event participation, submissions, payments, and other related activities. They are delivered via email and in-app (database) notifications. The locale for these notifications is typically determined by the exhibitor's preference or the application's current locale.

*   **`App\Notifications\Exhibitor\ExhibitorEditSubmissionRequestAccepted`**
    *   **Purpose:** Informs an exhibitor that their request to edit their submission has been approved. Includes the deadline for edits if applicable.
    *   **Queued:** Yes

*   **`App\Notifications\Exhibitor\ExhibitorEditSubmissionRequestRejected`**
    *   **Purpose:** Informs an exhibitor that their request to edit their submission has been rejected.
    *   **Queued:** Yes

*   **`App\Notifications\Exhibitor\ExhibitorEventRegistrationAccepted`**
    *   **Purpose:** Notifies an exhibitor that their overall event registration/submission has been accepted.
    *   **Queued:** Yes

*   **`App\Notifications\Exhibitor\ExhibitorGeneratedBadges`**
    *   **Purpose:** Informs an exhibitor that their event badges have been generated and are available.
    *   **Queued:** Yes

*   **`App\Notifications\Exhibitor\ExhibitorPaymentRegistrationAccepted`**
    *   **Purpose:** Confirms to an exhibitor that their payment for a specific payment slice/invoice has been successfully registered and accepted.
    *   **Queued:** Yes

*   **`App\Notifications\Exhibitor\ExhibitorPaymentRegistrationRejected`**
    *   **Purpose:** Informs an exhibitor that their submitted payment proof for a payment slice has been rejected.
    *   **Queued:** Yes

*   **`App\Notifications\Exhibitor\ExhibitorSubmissionProcessing`**
    *   **Purpose:** Notifies an exhibitor that their submitted application is currently under review or being processed.
    *   **Queued:** Yes

*   **`App\Notifications\Exhibitor\ExhibitorSubmissionRejected`**
    *   **Purpose:** Informs an exhibitor that their event registration/submission has been rejected, often including a reason.
    *   **Queued:** Yes

*   **`App\Notifications\Exhibitor\ExhibitorSubsequentPaymentAccepted`**
    *   **Purpose:** Notifies an exhibitor that a subsequent payment (e.g., for an additional service or a later installment) has been accepted.
    *   **Queued:** Yes

*   **`App\Notifications\Exhibitor\ExhibitorSubsequentPaymentRejected`**
    *   **Purpose:** Informs an exhibitor that their subsequent payment has been rejected.
    *   **Queued:** Yes

---

### Visitor Notifications

These notifications are sent to event visitors. They are delivered via email and in-app (database) notifications. The locale is based on user preference or application default.

*   **`App\Notifications\Visitor\VisitorEventRegistrationSuccessful`**
    *   **Purpose:** Confirms to a visitor that their registration for an event was successful.
    *   **Queued:** Yes

---

### General Notifications

These notifications can be targeted at various user types (Exhibitors, general Users) and are typically related to account management.

*   **`App\Notifications\ExhibitorWelcome`**
    *   **Purpose:** Sends a welcome email to a newly registered exhibitor, potentially including their initial login credentials (e.g., a generated password).
    *   **Queued:** Yes

*   **`App\Notifications\PasswordRegenerated`**
    *   **Purpose:** Informs a user (could be an Exhibitor or an Admin/User) that their password has been reset or regenerated by an administrator, providing the new password.
    *   **Queued:** Yes

