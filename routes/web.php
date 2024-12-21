<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});


Route::get("/zoo", function () {
    redirect("/admin/login");
})->name("login");
