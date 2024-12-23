<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('website.home');
});


Route::get("/redirect", function () {
    return redirect("/admin/login");
})->name("login");
