<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

// Task 3 will replace this with the real Services page route.
Route::get('/services', fn () => response('Coming soon.'))->name('services');
// Task 4 will replace this with the real Pricing Model page route.
Route::get('/pricing', fn () => response('Coming soon.'))->name('pricing');
// Task 5 will replace this with the real About page route.
Route::get('/about', fn () => response('Coming soon.'))->name('about');
// Task 6 will replace this with the real Contact page route.
Route::get('/contact', fn () => response('Coming soon.'))->name('contact');
