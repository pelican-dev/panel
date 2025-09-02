<?php

namespace App\Providers\Filament;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Panel;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->id('app')
            ->default()
            ->breadcrumbs(false)
            ->navigation(false)
            ->userMenuItems([
                'profile' => fn (Action $action) => $action->label(auth()->user()->username),
                Action::make('toAdmin')
                    ->label(trans('profile.admin'))
                    ->url(fn () => Filament::getPanel('admin')->getUrl())
                    ->icon('tabler-arrow-forward')
                    ->sort(5)
                    ->visible(fn () => auth()->user()->canAccessPanel(Filament::getPanel('admin'))),
            ])
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources');
    }
}
