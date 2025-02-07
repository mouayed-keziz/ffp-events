<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
class GuestController extends Controller
{
    public static function Home()
    {
        return view('website.pages.guest.home');
    }

    public static function Event()
    {
        return view('website.pages.guest.event');
    }

    public static function Articles()
    {
        return view('website.pages.guest.articles');
    }
    public static function Article($id) {
        $article = Article::findOrFail($id);
        $similarArticles = Article::where('id', '!=', $id)->limit(3)->get();
        return view('website.pages.guest.article', [
            'article' => $article,
            'viewCount' => $article->views,
            'similarArticles' => $similarArticles
        ]);
    }

    public static function SignIn()
    {
        return ["message" => "Sign In"];
    }
    public static function SignUp()
    {
        return ["message" => "Sign Up"];
    }
}
