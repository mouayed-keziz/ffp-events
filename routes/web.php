<?php

use App\Http\Controllers\Website\GuestController;
use App\Http\Controllers\Website\AuthController;
use App\Models\Article;
use App\Models\Category;
use App\Models\EventAnnouncement;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Add this new route at the beginning
Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'fr'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('language.switch');


Route::middleware('local_middleware')->group(function () {
    Route::get('/', [GuestController::class, 'Home'])->name('events')->middleware();
    Route::get('/event/{id}', [GuestController::class, 'Event'])->name('event_details');
    Route::get('/articles', [GuestController::class, 'Articles'])->name('articles');
    Route::get('/article/{slug}', [GuestController::class, 'Article'])->name('article');
    Route::prefix("auth")->group(function () {
        Route::get('/login', [AuthController::class, 'LogIn'])->name('login');
        Route::get('/register', [AuthController::class, 'Register'])->name('register');
        Route::get('/restore-account', [AuthController::class, 'RestoreAccount'])->name('restore-account');
        Route::get('/email-sent', [AuthController::class, 'EmailSent'])->name('email-sent');
        Route::get('/user', function () {
            $users = [
                'web' => Auth::guard('web')->check() ? Auth::guard('web')->user() : null,
                'visitor' => Auth::guard('visitor')->check() ? Auth::guard('visitor')->user() : null,
                'exhibitor' => Auth::guard('exhibitor')->check() ? Auth::guard('exhibitor')->user() : null,
            ];
            return $users;
        });
    });
});




Route::get("mouayed", function () {
    $eventAnnouncements = EventAnnouncement::with('visitorForm', 'exhibitorForms')->get();
    return $eventAnnouncements;
});
