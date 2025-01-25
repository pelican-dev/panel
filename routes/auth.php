<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth;

Route::redirect('/login', '/login')->name('auth.login');

Route::get('/oauth/redirect/{driver}', [Auth\OAuthController::class, 'redirect'])->name('auth.oauth.redirect');
Route::get('/oauth/callback/{driver}', [Auth\OAuthController::class, 'callback'])->name('auth.oauth.callback')->withoutMiddleware('guest');
