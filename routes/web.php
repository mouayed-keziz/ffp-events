<?php

use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('website.home');
});

Route::get('/blog', function () {
    return view('website.blog.index', [
        'articles' => Article::published()->latest()->paginate(9)
    ]);
})->name('blog.index');

Route::get('/categories', function () {
    return view('website.blog.categories', [
        'categories' => Category::withCount('articles')->get()
    ]);
})->name('blog.categories');

Route::get('/category/{category:slug}', function (Category $category) {
    return view('website.blog.category', [
        'category' => $category,
        'articles' => $category->articles()->published()->latest()->paginate(9)
    ]);
})->name('blog.category');

Route::get('/blog/{article:slug}', function (Article $article) {
    return view('website.blog.show', [
        'article' => $article
    ]);
})->name('blog.show');

Route::get("/redirect", function () {
    return redirect("/admin/login");
})->name("login");
