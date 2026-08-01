<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('/login', function () {
    return view('welcome');
})->name('login');
