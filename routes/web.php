<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});


Route::get("/zoo", function () {
    return redirect("/admin/login");
})->name("login");
