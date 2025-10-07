<?php

use App\Http\Controllers\Auth\OAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SearcadeController;

Route::redirect('/login', '/login')->name('auth.login');

Route::prefix('oauth')->group(function () {
    Route::get('/redirect/{driver}', [OAuthController::class, 'redirect'])->name('auth.oauth.redirect');
    Route::get('/callback/{driver}', [OAuthController::class, 'callback'])->name('auth.oauth.callback')->withoutMiddleware('guest');
});

// Endpoint for Searcade
Route::get('/login/searcade', [SearcadeController::class, 'login'])->name('auth.searcade.login');
