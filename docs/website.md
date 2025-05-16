# Website Documentation

This document describes the public-facing website of the 'ffp-events' Laravel application.

## Core Technologies

*   **Laravel:** The underlying PHP framework.
*   **Livewire:** Used for building dynamic interfaces with PHP, minimizing the need for extensive JavaScript.
*   **Blade:** Laravel's templating engine.

## Authentication

The website utilizes three distinct authentication guards to manage different user roles:

*   **`web`:** For general users (e.g., administrators accessing certain non-Filament website features, or potentially future registered users).
*   **`visitor`:** For event attendees (visitors).
*   **`exhibitor`:** For event exhibitors.

## Key Pages and Functionality

### General / Public Access

These pages are accessible to all users, including guests.

| Route                                      | Controller Action / View                    | Description                                                                                                |
| :----------------------------------------- | :------------------------------------------ | :--------------------------------------------------------------------------------------------------------- |
| `/`                                        | `EventController::Events`                   | Displays a list of upcoming or recent events.                                                              |
| `/event/{id}`                              | `EventController::Event`                    | Shows detailed information about a specific event, including related events.                               |
| `/articles`                                | `GuestController::Articles`                 | Lists published articles.                                                                                  |
| `/article/{slug}`                          | `GuestController::Article`                  | Displays a single article.                                                                                 |
| `/terms`                                   | `GuestController::Terms`                    | Shows the terms and conditions.                                                                            |
| `/language/{locale}`                       | -                                           | Allows users to switch the website's display language (supports 'en', 'ar', 'fr').                         |
| `/media/download/{id}`                     | `MediaController::download`                 | Allows downloading of specific media files.                                                                |
| `/auth/reset-password`                     | `AuthController::ResetPassword`             | Page for users to request a password reset.                                                                |
| `/redirect-to-ffp-events`                  | `GuestController::RedirectToFFPEvents`      | Redirects to the main FFP Events website.                                                                  |
| `/redirect-to-ffp-events-contact`          | `GuestController::RedirectToFFPEventsContact` | Redirects to the contact page of the FFP Events website.                                                   |

### Authenticated User Pages (General)

These pages require a user to be logged in (applies to any authenticated guard unless specified otherwise).

| Route                  | Controller Action / View        | Description                                                                 |
| :--------------------- | :------------------------------ | :-------------------------------------------------------------------------- |
| `/notifications`       | `website.pages.notifications`   | Displays in-app notifications for the logged-in user.                       |
| `/profile`             | `ProfileController::MyProfile`  | Allows users to view and manage their profile.                              |
| `/subscriptions`       | `ProfileController::MySubscriptions` | Allows users to manage their subscriptions.                                 |
| `/verify-email-change` | `ProfileController::verifyEmailChange` | Handles verification when a user changes their email address.             |

### Visitor Section

Functionality specific to event visitors. Requires visitor authentication.

| Route                               | Controller Action                       | Description                                                                                                                               |
| :---------------------------------- | :-------------------------------------- | :---------------------------------------------------------------------------------------------------------------------------------------- |
| `/event/{id}/visit`                 | `EventController::VisitEvent`           | Allows visitors to register for an event. Displays a form if visitor registration is open for the selected event.                         |
| `/event/{id}/visit-confirmation`    | `EventController::VisitFormSubmitted`   | Confirmation page shown after a visitor successfully submits their registration form for an event.                                        |
| `/event/{id}/download-badge`        | `EventController::DownloadVisitorBadge` | Allows registered visitors to download their event badge.                                                                                 |

### Exhibitor Section

Functionality specific to event exhibitors. Requires exhibitor authentication.

| Route                                                                 | Controller Action                             | Description                                                                                                                                                              |
| :-------------------------------------------------------------------- | :-------------------------------------------- | :----------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `/event/{id}/terms-and-conditions`                                    | `EventController::TermsAndConditions`         | Displays the terms and conditions specific to exhibitors for an event.                                                                                                   |
| `/event/{id}/manage-badges`                                           | `EventController::ManageExhibitorBadges`      | Allows exhibitors to manage their badges for an event (e.g., assign names, details).                                                                                     |
| `/exhibitor/events/{event}/submissions/{submission}/badges/download/{zipPath}` | `BadgeController::downloadBadgesZip`          | Allows authenticated exhibitors to download a ZIP file containing their generated badges.                                                                                |
| `/event/{id}/exhibit`  Registration                               | `EventController::ExhibitEvent`               | Page/form for exhibitors to apply to exhibit at an event.                                                                                                                |
| `/event/{id}/info-validation`                                       | `EventController::InfoValidation`             | Page for exhibitors to validate or update their information post-submission, potentially before payment or after approval.                                                 |
| `/event/{id}/submission`                                                 | `EventController::ViewExhibitorAnswers`       | Allows exhibitors to view the answers they provided in their submission forms.                                                                                           |
| `/event/{id}/download-invoice`                                             | `EventController::DownloadInvoice`            | Allows exhibitors to download their invoice for event participation fees.                                                                                                |
| `/event/{id}/upload-payment-proof`                                         | `EventController::UploadPaymentProof`         | Allows exhibitors to upload proof of payment for their participation.                                                                                                    |
| `/event/{id}/payment-validation`                                    | `EventController::PaymentValidation`          | Page for exhibitors to check the status of their payment validation.                                                                                                     |
| `/event/{id}/post-exhibition`                                        | `EventController::PostExhibitEvent`           | Access to forms that need to be filled out by exhibitors after their initial registration/payment is complete (e.g., for booth setup, additional services).             |
| `/clear-badge-redirect-session` (POST)                                | -                                             | Clears a session variable related to badge download redirection.                                                                                                         |


## Notifications

The website sends notifications to users (Visitors and Exhibitors) to keep them informed about important events and actions. These are typically delivered via Email and In-App (Database) channels.

