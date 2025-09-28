<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/scan', function () {
    return Inertia::render('Scan');
})->name('scan');

Route::get('/results/{uuid}', function ($uuid) {
    return Inertia::render('Results', [
        'submissionUuid' => $uuid
    ]);
})->name('results');

Route::get('/recommendations/{uuid}', function ($uuid) {
    return Inertia::render('ProductRecommendations', [
        'submissionUuid' => $uuid
    ]);
})->name('recommendations');

Route::get('/checkout', function () {
    return Inertia::render('Checkout');
})->name('checkout');
