<?php

namespace App\Providers\Filament;

use App\Filament\App\Resources\ServerResource\Pages\ListServers;
use App\Filament\Admin\Resources\ServerResource\Pages\EditServer;
use App\Filament\Pages\Auth\EditProfile;
use App\Http\Middleware\Activity\ServerSubject;
use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Panel;

class ServerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->id('server')
            ->path('server')
            ->homeUrl('/')
            ->tenant(Server::class)
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn () => trans('filament-panels::pages/auth/edit-profile.label'))
                    ->url(fn () => EditProfile::getUrl(panel: 'app')),
                MenuItem::make()
                    ->label('Server List')
                    ->icon('tabler-brand-docker')
                    ->url(fn () => ListServers::getUrl(panel: 'app'))
                    ->sort(6),
                MenuItem::make()
                    ->label(trans('profile.admin'))
                    ->icon('tabler-arrow-forward')
                    ->url(fn () => Filament::getPanel('admin')->getUrl())
                    ->sort(5)
                    ->visible(fn () => auth()->user()->canAccessPanel(Filament::getPanel('admin'))),
            ])
            ->navigationItems([
                NavigationItem::make(trans('server/console.open_in_admin'))
                    ->url(fn () => EditServer::getUrl(['record' => Filament::getTenant()], panel: 'admin'))
                    ->visible(fn () => auth()->user()->canAccessPanel(Filament::getPanel('admin')) && auth()->user()->can('view server', Filament::getTenant()))
                    ->icon('tabler-arrow-back')
                    ->sort(99),
            ])
            ->discoverResources(in: app_path('Filament/Server/Resources'), for: 'App\\Filament\\Server\\Resources')
            ->discoverPages(in: app_path('Filament/Server/Pages'), for: 'App\\Filament\\Server\\Pages')
            ->discoverWidgets(in: app_path('Filament/Server/Widgets'), for: 'App\\Filament\\Server\\Widgets')
            ->middleware([
                ServerSubject::class,
            ]);
    }
}
