<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/services', [PageController::class, 'services'])->name('services');

Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing');
// Task 5 will replace this with the real About page route.
Route::get('/about', fn () => response('Coming soon.'))->name('about');
// Task 6 will replace this with the real Contact page route.
Route::get('/contact', fn () => response('Coming soon.'))->name('contact');
