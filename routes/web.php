<?php

use App\Http\Controllers\Website\GuestController;
use App\Http\Controllers\Website\EventController;
use App\Http\Controllers\Website\AuthController;
use App\Http\Controllers\Website\ProfileController;
use App\Models\Article;
use App\Models\Category;
use App\Models\EventAnnouncement;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'fr'])) session()->put('locale', $locale);
    return redirect()->back();
})->name('language.switch');

Route::get('media/download/{id}', [\App\Http\Controllers\MediaController::class, 'download'])->name('media.download');

// Explicitly register Livewire routes to fix 404 errors in production
Livewire::setScriptRoute(function ($handle) {
    return Route::get('/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/livewire/update', $handle)
        ->middleware('web');
});

Route::prefix('admin')->middleware(['auth:web'])->group(function () {
    Route::get('exhibitor-submissions/{record}/download-invoice', [\App\Http\Controllers\Admin\ExhibitorSubmissionController::class, 'downloadInvoice'])
        ->name('admin.exhibitor_submissions.download_invoice');
});
Route::middleware('local_middleware')->group(function () {

    // Admin routes

    Route::view("/notifications", "website.pages.notifications")->name("notifications")->middleware("is_authenticated");
    Route::get("/profile",  [ProfileController::class, 'MyProfile'])->name("my-profile")->middleware("is_authenticated");
    Route::get("/subscriptions",  [ProfileController::class, 'MySubscriptions'])->name("my-subscriptions")->middleware("is_authenticated");
    Route::prefix("")->group(function () {
        Route::get('/', [EventController::class, 'Events'])->name('events');
        Route::get('/event/{id}', [EventController::class, 'Event'])->name('event_details');

        Route::get('/event/{id}/visit', [EventController::class, 'VisitEvent'])->name('visit_event')->middleware("is_visitor");
        Route::get('/event/{id}/visit-event-form-submitted', [EventController::class, 'VisitFormSubmitted'])->name('visit_event_form_submitted')->middleware("is_visitor");
        Route::middleware("is_exhibitor")->group(function () {
            Route::get("/event/{id}/terms-and-consitions", [EventController::class, 'TermsAndConditions'])->name('event_terms_and_conditions');
            Route::get('/event/{id}/exhibit', [EventController::class, 'ExhibitEvent'])->name('exhibit_event')->middleware("is_exhibitor");
            Route::get("/event/{id}/info-validation", [EventController::class, 'InfoValidation'])->name('info_validation');
            Route::get("/event/{id}/view-exhibitor-answers", [EventController::class, 'ViewExhibitorAnswers'])->name('view_exhibitor_answers');
            Route::get("/event/{id}/download-invoice", [EventController::class, 'DownloadInvoice'])->name('download_invoice');
            Route::get("/event/{id}/upload-payment-proof", [EventController::class, 'UploadPaymentProof'])->name('upload_payment_proof');
            Route::get("/event/{id}/payment-validation", [EventController::class, 'PaymentValidation'])->name('payment_validation');
            Route::get("/event/{id}/post-exhibit-event", [EventController::class, 'PostExhibitEvent'])->name('post_exhibit_event');
        });
    });

    Route::prefix("auth")->group(function () {
        Route::middleware("is_guest")->group(function () {
            Route::get('/login', [AuthController::class, 'LogIn'])->name('signin');
            // Route::post("/login", fn() => redirect()->route('signin'));
            Route::get('/register', [AuthController::class, 'Register'])->name('register');
            Route::get('/restore-account', [AuthController::class, 'RestoreAccount'])->name('restore-account');
            Route::get('/email-sent', [AuthController::class, 'EmailSent'])->name('email-sent');
            Route::get('/reset-password', [AuthController::class, 'ResetPassword'])->name('reset-password');
        });
    });

    Route::prefix("")->group(function () {
        Route::get('/articles', [GuestController::class, 'Articles'])->name('articles');
        Route::get('/article/{slug}', [GuestController::class, 'Article'])->name('article');
        Route::get("/terms", [GuestController::class, 'Terms'])->name('terms');
        Route::get("/redirect-to-ffp-events", [GuestController::class, 'RedirectToFFPEvents'])->name('redirect_to_ffp_events');
        Route::get("/redirect-to-ffp-events-contact", [GuestController::class, 'RedirectToFFPEventsContact'])->name('redirect_to_ffp_events_contact');
        Route::get('/verify-email-change', [ProfileController::class, 'verifyEmailChange'])->name('verify-email-change');
    });
});
