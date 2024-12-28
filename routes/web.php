<?php

use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

// Add this new route at the beginning
Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'fr'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('language.switch');


Route::middleware('locloooc')->group(function () {
    Route::get('/', function () {
        return view('website.home');
    });

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
