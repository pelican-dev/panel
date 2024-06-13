<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Base;
use App\Http\Middleware\RequireTwoFactorAuthentication;

Route::get('/', [Base\IndexController::class, 'index'])->name('index')->fallback();
Route::get('/account', [Base\IndexController::class, 'index'])
    ->withoutMiddleware(RequireTwoFactorAuthentication::class)
    ->name('account');

Route::get('/account/oauth/link', [Base\OAuthController::class, 'link'])->name('account.oauth.link');
Route::get('/account/oauth/unlink', [Base\OAuthController::class, 'unlink'])->name('account.oauth.unlink');

Route::get('/locales/locale.json', Base\LocaleController::class)
    ->withoutMiddleware(['auth', RequireTwoFactorAuthentication::class])
    ->where('namespace', '.*');

Route::get('/{react}', [Base\IndexController::class, 'index'])
    ->where('react', '^(?!(\/)?(api|auth|admin|daemon|legacy)).+');
