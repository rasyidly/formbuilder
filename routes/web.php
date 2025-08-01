<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/forms/{slug}', function ($slug) {
    return $slug;
})->name('forms.show');
