# Database Documentation

This document outlines the database schema for the Laravel application, which uses a **MySQL** database.

## Translatable Fields

Several models utilize translatable fields for multilingual support (Arabic, French, English). These fields are typically stored as JSON objects in the database. For example, a translatable `title` field might look like this:

```json
{
  "en": "Sample Title in English",
  "fr": "Exemple de Titre en Français",
  "ar": "مثال على عنوان باللغة العربية"
}
```

## Models

Below is a description of each Eloquent model and its corresponding database table structure.

---

### `Article`

Represents articles or blog posts.

*   **Table Name (assumed):** `articles`
*   **Fillable Attributes:** `title`, `slug`, 'description', `content`, `published_at`
*   **Translatable Attributes:** `title`, `description`, `content`
*   **Casts:** None explicitly defined in the provided snippet, beyond default Eloquent behavior.
*   **Relationships:**
    *   `categories()`: Many-to-Many with `Category`
*   **Media Collections:** Uses `InteractsWithMedia` (specific collections depend on `registerMediaCollections` implementation).
*   **Traits:** `SoftDeletes`, `HasVisits` (from Laravisit), `Shareable`

---

### `Badge`

Represents badges issued to visitors or exhibitors.

*   **Table Name (assumed):** `badges`
*   **Fillable Attributes:** `code`, `name`, `email`, `position`, `company`, `visitor_submission_id`, `exhibitor_submission_id`
*   **Casts:** None explicitly defined.
*   **Relationships:**
    *   `visitorSubmission()`: BelongsTo `VisitorSubmission`
    *   `exhibitorSubmission()`: BelongsTo `ExhibitorSubmission`
*   **Media Collections:**
    *   `image`: Single file

---

### `Banner`

Represents promotional banners.

*   **Table Name (assumed):** `banners`
*   **Fillable Attributes:** `title`, `url`, `order`, `is_active`
*   **Casts:**
    *   `is_active`: `boolean`
    *   `order`: `integer`
*   **Media Collections:**
    *   `banner`: Single file

---

### `Category`

Represents categories for articles.

*   **Table Name (assumed):** `categories`
*   **Fillable Attributes:** `name`, `slug`
*   **Translatable Attributes:** `name`
*   **Casts:** None explicitly defined.
*   **Relationships:**
    *   `articles()`: Many-to-Many with `Article`

---

### `EventAnnouncement`

Represents announcements for events.

*   **Table Name (assumed):** `event_announcements`
*   **Fillable Attributes:** (None explicitly defined in the `$fillable` array in the provided snippet)
*   **Translatable Attributes:** `title`, `description`, `terms`, `content`
*   **Casts:** None explicitly defined.
*   **Media Collections:** Uses `InteractsWithMedia` (specific collections depend on `registerMediaCollections` implementation).
*   **Traits:** `SoftDeletes`, `Shareable`

---

### `Exhibitor`

Represents exhibitor users.

*   **Table Name (assumed):** `exhibitors`
*   **Fillable Attributes:** `name`, `email`, `password`, `currency`, `verified_at`, `new_email`
*   **Hidden Attributes:** `password`, `remember_token`
*   **Casts:**
    *   `password`: `hashed`
    *   `verified_at`: `datetime`
    *   `currency`: `App\Enums\Currency` (Enum)
*   **Media Collections:**
    *   `image` (collection name based on `registerMediaCollections` snippet)
*   **Relationships:**
    *   `submissions()`: HasMany `ExhibitorSubmission`
*   **Traits:** `Notifiable`, `SoftDeletes`

---

### `ExhibitorForm`

Represents forms for exhibitors.

*   **Table Name (assumed):** `exhibitor_forms`
*   **Fillable Attributes:** `title`, `description`, `event_announcement_id`, `sections`
*   **Translatable Attributes:** `title`, `description`
*   **Casts:**
    *   `sections`: `array`
*   **Media Collections:**
    *   `images`: Single file
*   **Relationships:**
    *   `eventAnnouncement()`: BelongsTo `EventAnnouncement`

---

### `ExhibitorPaymentSlice`

Represents payment slices for exhibitor submissions.

*   **Table Name (assumed):** `exhibitor_payment_slices`
*   **Fillable Attributes:** `exhibitor_submission_id`, `sort`, `price`, `status`, `currency`, `due_to`
*   **Casts:**
    *   `status`: `App\Enums\PaymentSliceStatus` (Enum)
    *   `due_to`: `datetime`
*   **Media Collections:**
    *   `attachement`: Single file
*   **Relationships:**
    *   `exhibitorSubmission()`: BelongsTo `ExhibitorSubmission`

---

### `ExhibitorPostPaymentForm`

Represents forms for exhibitors after payment.

*   **Table Name (assumed):** `exhibitor_post_payment_forms`
*   **Fillable Attributes:** `title`, `description`, `event_announcement_id`, `sections`
*   **Translatable Attributes:** `title`, `description`
*   **Casts:**
    *   `sections`: `array`
*   **Media Collections:**
    *   `images`: Single file
*   **Relationships:**
    *   `eventAnnouncement()`: BelongsTo `EventAnnouncement`

---

### `ExhibitorSubmission`

Represents submissions made by exhibitors.

*   **Table Name (assumed):** `exhibitor_submissions`
*   **Fillable Attributes:** (None explicitly defined in the `$fillable` array in the provided snippet)
*   **Translatable Attributes:** `rejection_reason`
*   **Casts:** (None explicitly defined in the `$casts` array in the provided snippet, but likely includes status enums based on usage)
*   **Relationships:**
    *   `exhibitor()`: BelongsTo `Exhibitor`
    *   `eventAnnouncement()`: BelongsTo `EventAnnouncement`
*   **Media Collections:** Uses `InteractsWithMedia` (specific collections depend on `registerMediaCollections` implementation).

---

### `Export`

Represents data export records.

*   **Table Name (assumed):** `exports`
*   **Fillable Attributes:** `completed_at`, `file_disk`, `file_name`, `exporter`, `processed_rows`, `total_rows`, `successful_rows`, `user_id`
*   **Casts:**
    *   `completed_at`: `datetime`
    *   `exporter`: `App\Enums\ExportType` (Enum)
*   **Relationships:**
    *   `exported_by()`: BelongsTo `User`

---

### `Log` (extends `Spatie\Activitylog\Models\Activity`)

Customizes the Spatie Activity Log model. The actual table is `activity_log`.

*   **Table Name:** `activity_log` (managed by Spatie Activity Log package)
*   **Casts (on this custom model):**
    *   `event`: `App\Enums\LogEvent` (Enum)
    *   `log_name`: `App\Enums\LogName` (Enum)
    *   `properties`: `array`

---

### `Plan`

Represents pricing plans.

*   **Table Name (assumed):** `plans`
*   **Fillable Attributes:** `title`, `content`, `price`, `plan_tier_id`
*   **Translatable Attributes:** `title`, `content`
*   **Casts:**
    *   `price`: `array`
*   **Media Collections:**
    *   `image`: Single file
*   **Relationships:**
    *   `planTier()`: BelongsTo `PlanTier`

---

### `PlanTier`

Represents tiers for pricing plans.

*   **Table Name (assumed):** `plan_tiers`
*   **Fillable Attributes:** `title`
*   **Translatable Attributes:** `title`
*   **Casts:** None explicitly defined.
*   **Relationships:**
    *   `plans()`: HasMany `Plan`

---

### `Product`

Represents products.

*   **Table Name (assumed):** `products`
*   **Fillable Attributes:** `name`, `code`
*   **Translatable Attributes:** `name`
*   **Casts:** None explicitly defined.
*   **Media Collections:**
    *   `image`: Single file
*   **Traits:** `SoftDeletes`

---

### `Role` (extends `Spatie\Permission\Models\Role`)

Customizes the Spatie Permission Role model. The actual table is `roles`.

*   **Table Name:** `roles` (managed by Spatie Permission package)
*   **Casts (on this custom model):**
    *   `name`: `App\Enums\Role` (Enum)

---

### `Share`

Represents share actions on social media platforms.

*   **Table Name (assumed):** `shares`
*   **Fillable Attributes:** `platform`
*   **Casts:** None explicitly defined.

---

### `User`

Represents general users of the system (e.g., administrators).

*   **Table Name (assumed):** `users`
*   **Fillable Attributes:** (None explicitly defined in the `$fillable` array in the provided snippet)
*   **Hidden Attributes:** (None explicitly defined in the `$hidden` array in the provided snippet, typically `password`, `remember_token`)
*   **Casts:** (None explicitly defined in the `$casts` array in the provided snippet, typically `email_verified_at` => `datetime`)
*   **Media Collections:** Uses `InteractsWithMedia` (specific collections depend on `registerMediaCollections` implementation).
*   **Relationships:**
    *   `roles()`: MorphToMany with `Role` (Spatie Permission)
*   **Traits:** `Notifiable`, `HasRoles` (Spatie Permission), `SoftDeletes`
*   **Implements:** `FilamentUser`

---

### `Visitor`

Represents visitor users.

*   **Table Name (assumed):** `visitors`
*   **Fillable Attributes:** `name`, `email`, `password`, `verified_at`, `new_email`
*   **Hidden Attributes:** `password`, `remember_token`
*   **Casts:**
    *   `password`: `hashed`
    *   `verified_at`: `datetime`
*   **Media Collections:**
    *   `image` (collection name based on `registerMediaCollections` snippet)
*   **Relationships:**
    *   `submissions()`: HasMany `VisitorSubmission`
*   **Traits:** `Notifiable`, `SoftDeletes`

---

### `VisitorForm`

Represents forms for visitors.

*   **Table Name (assumed):** `visitor_forms`
*   **Fillable Attributes:** `event_announcement_id`, `sections`
*   **Casts:**
    *   `sections`: `array`
*   **Relationships:**
    *   `eventAnnouncement()`: BelongsTo `EventAnnouncement`

---

### `VisitorSubmission`

Represents submissions made by visitors.

*   **Table Name (assumed):** `visitor_submissions`
*   **Fillable Attributes:** `visitor_id`, `event_announcement_id`, `answers`, `status`
*   **Casts:**
    *   `answers`: `array`
    *   `status`: `App\Enums\SubmissionStatus` (Enum)
*   **Media Collections:** Uses `InteractsWithMedia` (specific collections depend on `registerMediaCollections` implementation).
*   **Relationships:**
    *   `visitor()`: BelongsTo `Visitor`
    *   `eventAnnouncement()`: BelongsTo `EventAnnouncement`
    *   `badge()`: (Likely HasOne or MorphOne relationship with `Badge`)

---

## Package-Specific Tables

This application utilizes several Laravel packages that manage their own database tables.

### Spatie Media Library (`media` table)

The `spatie/laravel-medialibrary` package is used for managing file uploads and associating them with models. It creates and uses a `media` table to store information about all media items, including their file names, disk, size, manipulations, custom properties, etc. Models using the `InteractsWithMedia` trait can have media associated with them.

### Spatie Permissions

The `spatie/laravel-permission` package handles roles and permissions. It typically creates the following tables:
*   `roles`: Stores role definitions.
*   `permissions`: Stores permission definitions.
*   `model_has_permissions`: Associates permissions directly with individual models (users).
*   `model_has_roles`: Associates roles with individual models (users).
*   `role_has_permissions`: Associates permissions with roles.

### Spatie Laravel Settings (`settings` table)

The `spatie/laravel-settings` package is used for managing application settings. It creates and uses a `settings` table to store these settings. The specific settings themselves will be detailed in a separate document.

### Spatie Activity Log (`activity_log` table)

The `spatie/laravel-activitylog` package is used for logging user activities and model changes. It creates and uses an `activity_log` table to store these logs. This application uses a custom `App\Models\Log` model that extends the base activity model to add custom behavior and casts.

### Laravisit (`visits` table)

The `coderflex/laravisit` package is used for tracking model visits (e.g., page views for articles). It creates and uses a `visits` table to store visit data, including the model being visited and, optionally, the visitor.
