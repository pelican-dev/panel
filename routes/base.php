<?php

use App\Livewire\Installer\PanelInstaller;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RequireTwoFactorAuthentication;

Route::get('installer', PanelInstaller::class)->name('installer')
    ->withoutMiddleware(['auth', RequireTwoFactorAuthentication::class]);
