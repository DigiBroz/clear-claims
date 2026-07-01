<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

// Placeholder routes so shared layout links resolve before their pages are built in Tasks 3-7.
Route::get('/services', fn () => response('Coming soon.'))->name('services');
Route::get('/pricing', fn () => response('Coming soon.'))->name('pricing');
Route::get('/about', fn () => response('Coming soon.'))->name('about');
Route::get('/contact', fn () => response('Coming soon.'))->name('contact');
