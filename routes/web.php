<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// TODO: Add auth middleware (Story 0.3)
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
