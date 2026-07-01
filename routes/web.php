<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/services', [PageController::class, 'services'])->name('services');

Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing');

Route::get('/about', [PageController::class, 'about'])->name('about');

Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Route::post('/contact', [ContactController::class, 'submit'])
    ->middleware([ProtectAgainstSpam::class, 'throttle:5,1'])
    ->name('contact.submit');

Route::get('/sitemap.xml', function () {
    return response(file_get_contents(public_path('sitemap.xml')), 200, ['Content-Type' => 'application/xml']);
});

Route::get('/robots.txt', function () {
    return response(file_get_contents(public_path('robots.txt')), 200, ['Content-Type' => 'text/plain']);
});
