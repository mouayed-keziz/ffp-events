<?php

use App\Http\Controllers\Website\GuestController;
use App\Models\Article;
use App\Models\Category;
use App\Models\EventAnnouncement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

// Add this new route at the beginning
Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'fr'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('language.switch');


Route::middleware('local_middleware')->group(function () {
    Route::get('/', [GuestController::class, 'Home'])->name('events');
    Route::get('/event', [GuestController::class, 'Event'])->name('event_details');
    Route::get('/articles', [GuestController::class, 'Articles'])->name('articles');
    Route::get('/se-connecter', [GuestController::class, 'SignIn'])->name('sign_in');
    Route::get('/s\'inscrire', [GuestController::class, 'SignUp'])->name('sign_up');

    Route::get('/blog', function () {
        return view('website.pages.blog.index', [
            'articles' => Article::published()->latest()->paginate(9),
            'totalViews' => 9999,
        ]);
    })->name('blog.index');

    Route::get('/categories', function () {
        return view('website.pages.blog.categories', [
            'categories' => Category::withCount('articles')->get()
        ]);
    })->name('blog.categories');

    Route::get('/category/{id}', function (int $id) {
        $category = Category::findOrFail($id);

        return view('website.pages.blog.category', [
            'category' => $category,
            'articles' => $category->articles()->published()->latest()->paginate(9)
        ]);
    })->name('blog.category');

    Route::get('/blog/{id}', function (int $id) {
        $article = Article::findOrFail($id);

        return view('website.pages.blog.show', [
            'article' => $article,
            'viewCount' => $article->views
        ]);
    })->name('blog.show');
});

Route::get("/redirect", function () {
    return redirect("/admin/login");
})->name("login");



Route::get("mouayed", function () {
    $eventAnnouncements = EventAnnouncement::with('visitorForm', 'exhibitorForms')->get();
    return $eventAnnouncements;
});
