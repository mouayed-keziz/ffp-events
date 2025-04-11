<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public static function MyProfile()
    {
        return view('website.pages.profile.my-profile');
    }
    public static function MySubscriptions()
    {
        return view('website.pages.profile.my-subscriptions');
    }
}
