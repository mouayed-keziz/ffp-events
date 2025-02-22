<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\EventAnnouncement;

class GuestController extends Controller
{
    public static function Events()
    {
        $events = EventAnnouncement::orderBy('created_at', 'desc')->limit(10)->get();
        return view('website.pages.guest.home', [
            'events' => $events
        ]);
    }

    public static function Event($id)
    {
        $event = EventAnnouncement::find($id);
        $relatedEvents = EventAnnouncement::where('id', '!=', $id)->inRandomOrder()->limit(4)->get();
        if (!$event) {
            return redirect()->route('events');
        }
        return view('website.pages.guest.event', [
            'event' => $event,
            'relatedEvents' => $relatedEvents
        ]);
    }

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
