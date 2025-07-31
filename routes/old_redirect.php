<?php

use App\Http\Controllers\Website\GuestController;
use App\Http\Controllers\Website\EventController;
use App\Http\Controllers\Website\AuthController;
use App\Http\Controllers\Website\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\EventAnnouncement;

// Redirect old English routes to new French routes
Route::middleware('local_middleware')->group(function () {

    // Profile & Main Routes
    Route::get('/profile', function () {
        return redirect('/profil', 301);
    });

    Route::get('/subscriptions', function () {
        return redirect('/mes-inscriptions', 301);
    });

    // OLD ENGLISH ROUTES WITH IDs (redirect to new French slug-based routes)
    Route::get('/event/{id}', function ($id) {
        $event = EventAnnouncement::find($id);
        if ($event) {
            return redirect("/evenement/{$event->slug}", 301);
        }
        return redirect('/', 301);
    })->where('id', '[0-9]+');

    Route::get('/event/{id}/visit-event', function ($id) {
        $event = EventAnnouncement::find($id);
        if ($event) {
            return redirect("/evenement/{$event->slug}/visiter-evenement", 301);
        }
        return redirect('/', 301);
    })->where('id', '[0-9]+');

    Route::get('/event/{id}/visit', function ($id) {
        $event = EventAnnouncement::find($id);
        if ($event) {
            return redirect("/evenement/{$event->slug}/visiter", 301);
        }
        return redirect('/', 301);
    })->where('id', '[0-9]+');

    Route::get('/event/{id}/visit-confirmation', function ($id) {
        $event = EventAnnouncement::find($id);
        if ($event) {
            return redirect("/evenement/{$event->slug}/confirmation-visite", 301);
        }
        return redirect('/', 301);
    })->where('id', '[0-9]+');

    Route::get('/event/{id}/visit-anonymous-confirmation', function ($id) {
        $event = EventAnnouncement::find($id);
        if ($event) {
            return redirect("/evenement/{$event->slug}/confirmation-visite-anonyme", 301);
        }
        return redirect('/', 301);
    })->where('id', '[0-9]+');

    Route::get('/event/{id}/download-badge', function ($id) {
        $event = EventAnnouncement::find($id);
        if ($event) {
            return redirect("/evenement/{$event->slug}/telecharger-badge", 301);
        }
        return redirect('/', 301);
    })->where('id', '[0-9]+');

    Route::get('/event/{id}/terms-and-conditions', function ($id) {
        $event = EventAnnouncement::find($id);
        if ($event) {
            return redirect("/evenement/{$event->slug}/conditions-generales", 301);
        }
        return redirect('/', 301);
    })->where('id', '[0-9]+');

    Route::get('/event/{id}/exhibit', function ($id) {
        $event = EventAnnouncement::find($id);
        if ($event) {
            return redirect("/evenement/{$event->slug}/exposer", 301);
        }
        return redirect('/', 301);
    })->where('id', '[0-9]+');

    Route::get('/event/{id}/info-validation', function ($id) {
        $event = EventAnnouncement::find($id);
        if ($event) {
            return redirect("/evenement/{$event->slug}/validation-info", 301);
        }
        return redirect('/', 301);
    })->where('id', '[0-9]+');

    Route::get('/event/{id}/submission', function ($id) {
        $event = EventAnnouncement::find($id);
        if ($event) {
            return redirect("/evenement/{$event->slug}/soumission", 301);
        }
        return redirect('/', 301);
    })->where('id', '[0-9]+');

    Route::get('/event/{id}/download-invoice', function ($id) {
        $event = EventAnnouncement::find($id);
        if ($event) {
            return redirect("/evenement/{$event->slug}/telecharger-facture", 301);
        }
        return redirect('/', 301);
    })->where('id', '[0-9]+');

    Route::get('/event/{id}/upload-payment-proof', function ($id) {
        $event = EventAnnouncement::find($id);
        if ($event) {
            return redirect("/evenement/{$event->slug}/telecharger-preuve-paiement", 301);
        }
        return redirect('/', 301);
    })->where('id', '[0-9]+');

    Route::get('/event/{id}/payment-validation', function ($id) {
        $event = EventAnnouncement::find($id);
        if ($event) {
            return redirect("/evenement/{$event->slug}/validation-paiement", 301);
        }
        return redirect('/', 301);
    })->where('id', '[0-9]+');

    Route::get('/event/{id}/post-exhibition', function ($id) {
        $event = EventAnnouncement::find($id);
        if ($event) {
            return redirect("/evenement/{$event->slug}/post-exposition", 301);
        }
        return redirect('/', 301);
    })->where('id', '[0-9]+');

    Route::get('/event/{id}/manage-badges', function ($id) {
        $event = EventAnnouncement::find($id);
        if ($event) {
            return redirect("/evenement/{$event->slug}/gerer-badges", 301);
        }
        return redirect('/', 301);
    })->where('id', '[0-9]+');

    // ENGLISH ROUTES WITH SLUGS (redirect to new French slug-based routes)
    Route::get('/event/{slug}', function ($slug) {
        return redirect("/evenement/{$slug}", 301);
    });

    Route::get('/event/{slug}/visit-event', function ($slug) {
        return redirect("/evenement/{$slug}/visiter-evenement", 301);
    });

    Route::get('/event/{slug}/visit', function ($slug) {
        return redirect("/evenement/{$slug}/visiter", 301);
    });

    Route::get('/event/{slug}/visit-confirmation', function ($slug) {
        return redirect("/evenement/{$slug}/confirmation-visite", 301);
    });

    Route::get('/event/{slug}/visit-anonymous-confirmation', function ($slug) {
        return redirect("/evenement/{$slug}/confirmation-visite-anonyme", 301);
    });

    Route::get('/event/{slug}/download-badge', function ($slug) {
        return redirect("/evenement/{$slug}/telecharger-badge", 301);
    });

    // Exhibitor Routes
    Route::get('/event/{slug}/terms-and-conditions', function ($slug) {
        return redirect("/evenement/{$slug}/conditions-generales", 301);
    });

    Route::get('/event/{slug}/exhibit', function ($slug) {
        return redirect("/evenement/{$slug}/exposer", 301);
    });

    Route::get('/event/{slug}/info-validation', function ($slug) {
        return redirect("/evenement/{$slug}/validation-info", 301);
    });

    Route::get('/event/{slug}/submission', function ($slug) {
        return redirect("/evenement/{$slug}/soumission", 301);
    });

    Route::get('/event/{slug}/download-invoice', function ($slug) {
        return redirect("/evenement/{$slug}/telecharger-facture", 301);
    });

    Route::get('/event/{slug}/upload-payment-proof', function ($slug) {
        return redirect("/evenement/{$slug}/telecharger-preuve-paiement", 301);
    });

    Route::get('/event/{slug}/payment-validation', function ($slug) {
        return redirect("/evenement/{$slug}/validation-paiement", 301);
    });

    Route::get('/event/{slug}/post-exhibition', function ($slug) {
        return redirect("/evenement/{$slug}/post-exposition", 301);
    });

    Route::get('/event/{slug}/manage-badges', function ($slug) {
        return redirect("/evenement/{$slug}/gerer-badges", 301);
    });

    // Auth Routes (old auth prefix)
    Route::prefix("auth")->group(function () {
        Route::get('/login', function () {
            return redirect('/authentification/connexion', 301);
        });

        Route::get('/redirect-login', function () {
            return redirect('/authentification/redirection-connexion', 301);
        });

        Route::get('/register', function () {
            return redirect('/authentification/inscription', 301);
        });

        Route::get('/forgot-password', function () {
            return redirect('/authentification/mot-de-passe-oublie', 301);
        });

        Route::get('/email-sent', function () {
            return redirect('/authentification/email-envoye', 301);
        });

        Route::get('/reset-password', function () {
            return redirect('/authentification/reinitialiser-mot-de-passe', 301);
        });
    });

    // Guest Routes
    Route::get('/terms', function () {
        return redirect('/conditions', 301);
    });

    Route::get('/redirect-to-ffp-events', function () {
        return redirect('/redirection-vers-ffp-evenements', 301);
    });

    Route::get('/redirect-to-ffp-events-contact', function () {
        return redirect('/redirection-vers-ffp-evenements-contact', 301);
    });

    Route::get('/verify-email-change', function () {
        return redirect('/verifier-changement-email', 301);
    });
});
