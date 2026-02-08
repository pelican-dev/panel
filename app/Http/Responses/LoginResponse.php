<?php

namespace App\Http\Responses;

use App\Enums\CustomizationKey;
use App\Models\User;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        /** @var User|null $user */
        $user = Filament::auth()->user();

        if ($user?->getCustomization(CustomizationKey::RedirectToAdmin) && $user->canAccessPanel(Filament::getPanel('admin'))) {
            return redirect()->intended(Filament::getPanel('admin')->getUrl());
        }

        return redirect()->intended(Filament::getUrl());
    }
}
