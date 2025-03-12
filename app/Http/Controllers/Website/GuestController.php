<?php

namespace App\Http\Controllers\Website;

use App\Enums\ArticleStatus;
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
        if (!$article->status === ArticleStatus::Published) {
            return redirect()->route('articles');
        }
        $article->visit()->withIp();
        $similarArticles = Article::where('slug', '!=', $slug)->limit(3)->get();
        return view('website.pages.guest.article', [
            'article' => $article,
            'viewCount' => $article->views,
            'similarArticles' => $similarArticles
        ]);
    }

    public static function Terms()
    {
        return view("website.pages.guest.terms");
    }

    public static function RedirectToFFPEvents()
    {
        return redirect()->away('https://ffp-events.com/');
    }
    public static function RedirectToFFPEventsContact()
    {
        return redirect()->away('https://ffp-events.com/contact/');
    }
}
