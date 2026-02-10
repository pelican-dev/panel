<?php

use App\Http\Controllers\Auth\OAuthController;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPasskeys\Http\Controllers\AuthenticateUsingPasskeyController;
use Spatie\LaravelPasskeys\Http\Controllers\GeneratePasskeyAuthenticationOptionsController;

Route::redirect('/login', '/login')->name('auth.login');

Route::prefix('oauth')->group(function () {
    Route::get('/redirect/{driver}', [OAuthController::class, 'redirect'])->name('auth.oauth.redirect');
    Route::get('/callback/{driver}', [OAuthController::class, 'callback'])->name('auth.oauth.callback')->withoutMiddleware('guest');
});

Route::prefix('auth/passkeys')->group(function () {
    Route::get('/authentication-options', GeneratePasskeyAuthenticationOptionsController::class)->name('passkeys.authentication_options');
    Route::post('/authenticate', AuthenticateUsingPasskeyController::class)->name('passkeys.login');
});
