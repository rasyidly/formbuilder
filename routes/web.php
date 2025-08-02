<?php

use App\Livewire\Pages;
use Illuminate\Support\Facades\Route;

Route::get('/', Pages\Index::class)->name('index');

Route::get('/forms/{slug}', Pages\Forms\Show::class)->name('forms.show');
