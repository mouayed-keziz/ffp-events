<?php

use App\Http\Controllers\BadgeController;
use App\Http\Controllers\Website\GuestController;
use App\Http\Controllers\Website\EventController;
use App\Http\Controllers\Website\AuthController;
use App\Http\Controllers\Website\ProfileController;
use App\Http\Controllers\BadgePreviewController;
use App\Models\Article;
use App\Models\Category;
use App\Models\EventAnnouncement;
use Filament\Actions\Exports\Http\Controllers\DownloadExport;
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
    Route::get('exhibitor-submissions/{record}/download-invoice', [\App\Http\Controllers\Admin\ExhibitorSubmissionController::class, 'downloadInvoice'])->name('admin.exhibitor_submissions.download_invoice');

    // QR Scanner API routes for admin panel
    Route::post('qr-scanner/process-scan', [\App\Http\Controllers\Api\QrScannerController::class, 'processScan'])->name('admin.qr-scanner.process-scan');
    Route::post('qr-scanner/download-badge', [\App\Http\Controllers\Api\QrScannerController::class, 'downloadBadge'])->name('admin.qr-scanner.download-badge');

    // Download database backup (requires auth:web)
    Route::get('database-backup/download', function () {
        $path = storage_path('app/private/database_backup.sqlite');

        if (! file_exists($path)) {
            abort(404, 'Backup file not found.');
        }

        return response()->download($path, 'database_backup.sqlite');
    })->name('admin.database-backup.download');
});
Route::middleware('local_middleware')->group(function () {
    Route::view("/notifications", "website.pages.notifications")->name("notifications")->middleware("is_authenticated");
    Route::get("/profil",  [ProfileController::class, 'MyProfile'])->name("my-profile")->middleware("is_authenticated");
    Route::get("/mes-inscriptions",  [ProfileController::class, 'MySubscriptions'])->name("my-subscriptions")->middleware("is_authenticated");
    Route::prefix("")->group(function () {
        Route::get('/', [EventController::class, 'Events'])->name('events');
        Route::get('/evenement/{slug}', [EventController::class, 'Event'])->name('event_details');

        Route::get('/evenement/{slug}/visiter-evenement', [EventController::class, 'VisitEvent'])->name('visit_event')->middleware("is_visitor");
        Route::get('/evenement/{slug}/visiter', [EventController::class, 'VisitEventAnonymous'])->name('visit_event_anonymous');
        Route::get('/evenement/{slug}/confirmation-visite', [EventController::class, 'VisitFormSubmitted'])->name('visit_event_form_submitted')->middleware("is_visitor");
        Route::get('/evenement/{slug}/confirmation-visite-anonyme', [EventController::class, 'VisitAnonymousFormSubmitted'])->name('visit_event_anonymous_form_submitted');
        Route::get('/evenement/{slug}/telecharger-badge', [EventController::class, 'DownloadVisitorBadge'])->name('download_visitor_badge')->middleware("is_visitor");
        Route::middleware("is_exhibitor")->group(function () {
            Route::get("/evenement/{slug}/conditions-generales", [EventController::class, 'TermsAndConditions'])->name('event_terms_and_conditions');
            Route::get('/evenement/{slug}/exposer', [EventController::class, 'ExhibitEvent'])->name('exhibit_event')->middleware("is_exhibitor");
            Route::get("/evenement/{slug}/validation-info", [EventController::class, 'InfoValidation'])->name('info_validation');
            Route::get("/evenement/{slug}/soumission", [EventController::class, 'ViewExhibitorAnswers'])->name('view_exhibitor_answers');
            Route::get("/evenement/{slug}/telecharger-facture", [EventController::class, 'DownloadInvoice'])->name('download_invoice');
            Route::get("/evenement/{slug}/telecharger-preuve-paiement", [EventController::class, 'UploadPaymentProof'])->name('upload_payment_proof');
            Route::get("/evenement/{slug}/validation-paiement", [EventController::class, 'PaymentValidation'])->name('payment_validation');
            Route::get("/evenement/{slug}/post-exposition", [EventController::class, 'PostExhibitEvent'])->name('post_exhibit_event');
            Route::get("/evenement/{slug}/gerer-badges", [EventController::class, 'ManageExhibitorBadges'])->name('manage_exhibitor_badges');
        });
    });

    Route::prefix("authentification")->group(function () {
        Route::middleware("is_guest")->group(function () {
            Route::get('/connexion', [AuthController::class, 'LogIn'])->name('signin');
            Route::redirect("/redirection-connexion", '/authentification/connexion')->name("login");
            Route::get('/inscription', [AuthController::class, 'Register'])->name('register');
            Route::get('/mot-de-passe-oublie', [AuthController::class, 'RestoreAccount'])->name('restore-account');
            Route::get('/email-envoye', [AuthController::class, 'EmailSent'])->name('email-sent');
            Route::get('/reinitialiser-mot-de-passe', [AuthController::class, 'ResetPassword'])->name('reset-password');
        });
    });

    Route::prefix("")->group(function () {
        Route::get('/articles', [GuestController::class, 'Articles'])->name('articles');
        Route::get('/article/{slug}', [GuestController::class, 'Article'])->name('article');
        Route::get("/conditions", [GuestController::class, 'Terms'])->name('terms');
        Route::get("/redirection-vers-ffp-evenements", [GuestController::class, 'RedirectToFFPEvents'])->name('redirect_to_ffp_events');
        Route::get("/redirection-vers-ffp-evenements-contact", [GuestController::class, 'RedirectToFFPEventsContact'])->name('redirect_to_ffp_events_contact');
        Route::get('/verifier-changement-email', [ProfileController::class, 'verifyEmailChange'])->name('verify-email-change');
    });
});

Route::get('/exhibitor/events/{event:id}/submissions/{submission}/badges/download/{zipPath}', [BadgeController::class, 'downloadBadgesZip'])
    ->middleware('auth:exhibitor')
    ->name('exhibitor.badges.download');

Route::post('/clear-badge-redirect-session', function () {
    session()->forget('badge_download_redirect');
    return response()->json(['success' => true]);
})->middleware('auth:exhibitor')->name('clear.badge.redirect.session');

require __DIR__ . '/old_redirect.php';

// Route::get('/badge-preview', [BadgeController::class, 'show'])->name('badge.preview');
// Route::get('/badge-preview/blank', [BadgeController::class, 'showBlank'])->name('badge.preview.blank');
