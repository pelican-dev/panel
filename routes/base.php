<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Installer\PanelInstaller;
use App\Http\Middleware\RequireTwoFactorAuthentication;

Route::get('installer', PanelInstaller::class)->name('installer')
    ->withoutMiddleware(['auth', RequireTwoFactorAuthentication::class]);
