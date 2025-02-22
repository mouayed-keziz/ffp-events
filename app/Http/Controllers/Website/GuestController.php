<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\EventAnnouncement;

class GuestController extends Controller
{

    public static function Articles()
    {
        return view('website.pages.guest.articles');
    }
    public static function Article($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        $similarArticles = Article::where('slug', '!=', $slug)->limit(3)->get();
        return view('website.pages.guest.article', [
            'article' => $article,
            'viewCount' => $article->views,
            'similarArticles' => $similarArticles
        ]);
    }
}
