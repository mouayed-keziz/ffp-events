<?php

use App\Http\Controllers\Website\GuestController;
use App\Http\Controllers\Website\EventController;
use App\Http\Controllers\Website\AuthController;
use App\Models\Article;
use App\Models\Category;
use App\Models\EventAnnouncement;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'fr'])) session()->put('locale', $locale);
    return redirect()->back();
})->name('language.switch');

Route::get('media/download/{id}', [\App\Http\Controllers\MediaController::class, 'download'])->name('media.download');

Route::middleware('local_middleware')->group(function () {

    Route::prefix("")->group(function () {
        Route::get('/', [EventController::class, 'Events'])->name('events');
        Route::get('/event/{id}', [EventController::class, 'Event'])->name('event_details');

        Route::get('/event/{id}/visit', [EventController::class, 'VisitEvent'])->name('visit_event')->middleware("is_visitor");
        Route::get('/event/{id}/visit-event-form-submitted', [EventController::class, 'VisitFormSubmitted'])->name('visit_event_form_submitted')->middleware("is_visitor");

        Route::get('/event/{id}/exhibit', [EventController::class, 'ExhibitEvent'])->name('exhibit_event')->middleware("is_exhibitor");
        Route::get("/event/{id}/info-validation", [EventController::class, 'InfoValidation'])->name('info_validation')->middleware("is_exhibitor");
        Route::get("/event/{id}/view-exhibitor-answers", [EventController::class, 'ViewExhibitorAnswers'])->name('view_exhibitor_answers')->middleware("is_exhibitor");
        Route::get("/event/{id}/download-invoice", [EventController::class, 'DownloadInvoice'])->name('download_invoice')->middleware("is_exhibitor");
        Route::get("/event/{id}/upload-payment-proof", [EventController::class, 'UploadPaymentProof'])->name('upload_payment_proof')->middleware("is_exhibitor");
        Route::get("/event/{id}/payment-validation", [EventController::class, 'PaymentValidation'])->name('payment_validation')->middleware("is_exhibitor");
    });

    Route::prefix("auth")->group(function () {
        Route::middleware("is_guest")->group(function () {
            Route::get('/login', [AuthController::class, 'LogIn'])->name('login');
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
    });
});
