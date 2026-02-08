<?php

namespace App\Providers;

use App\Enums\TabPosition;
use App\Filament\Pages\Auth\EditProfile;
use App\Http\Controllers\PwaController;
use App\Http\Controllers\PwaPushController;
use App\Listeners\SendPwaPushOnDatabaseNotification;
use App\Services\Pwa\PwaActions;
use App\Services\Pwa\PwaSettingsRepository;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;

class PwaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerListener();
        $this->registerHeadHook();
        $this->registerProfileTab();
    }

    private function registerRoutes(): void
    {
        if (Route::has('pwa.manifest')) {
            return;
        }

        Route::middleware('web')->group(function () {
            Route::get('/manifest.json', [PwaController::class, 'manifest'])->name('pwa.manifest');
            Route::get('/service-worker.js', [PwaController::class, 'serviceWorker'])->name('pwa.sw');

            Route::middleware('auth')->group(function () {
                Route::post('/pwa/subscribe', [PwaPushController::class, 'subscribe'])->name('pwa.subscribe');
                Route::post('/pwa/unsubscribe', [PwaPushController::class, 'unsubscribe'])->name('pwa.unsubscribe');
                Route::post('/pwa/test', [PwaPushController::class, 'test'])->name('pwa.test');
            });
        });
    }

    private function registerListener(): void
    {
        Event::listen(NotificationSent::class, SendPwaPushOnDatabaseNotification::class);
    }

    private function registerHeadHook(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn (): HtmlString => $this->getPwaHeadHtml()
        );
    }

    private function getPwaHeadHtml(): HtmlString
    {
        $settings = $this->app->make(PwaSettingsRepository::class);

        $appName = e(config('app.name', 'Pelican Panel'));

        $themeColor = e($settings->get('theme_color', '#0ea5e9'));

        $favicon = e(config('app.favicon', '/pelican.ico'));

        $appleDefault = e(asset(ltrim($settings->get('apple_touch_icon', '/pelican-180.png'), '/')));
        $apple152 = e(asset(ltrim($settings->get('apple_touch_icon_152', '/pelican-152.png'), '/')));
        $apple167 = e(asset(ltrim($settings->get('apple_touch_icon_167', '/pelican-167.png'), '/')));
        $apple180 = e(asset(ltrim($settings->get('apple_touch_icon_180', '/pelican-180.png'), '/')));

        $vapidPublicKey = json_encode($settings->get('vapid_public_key', ''));
        $pushEnabled = json_encode((bool) $settings->get('push_enabled', false));

        $langUpdateAvailable = json_encode(trans('pwa.messages.update_available'));

        $html = <<<HTML
        <meta name="application-name" content="{$appName}">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="{$appName}">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="theme-color" content="{$themeColor}">

        <link rel="manifest" href="/manifest.json">
        <link rel="icon" href="{$favicon}" type="image/x-icon">
        <link rel="apple-touch-icon" href="{$appleDefault}">
        <link rel="apple-touch-icon" sizes="152x152" href="{$apple152}">
        <link rel="apple-touch-icon" sizes="180x180" href="{$apple180}">
        <link rel="apple-touch-icon" sizes="167x167" href="{$apple167}">

        <script>
        window.pwaConfig = {
            vapidPublicKey: {$vapidPublicKey},
            pushEnabled: {$pushEnabled},
            routes: {
                subscribe: "/pwa/subscribe",
                unsubscribe: "/pwa/unsubscribe",
                test: "/pwa/test",
            },
            lang: {
                updateAvailable: {$langUpdateAvailable}
            }
        };

        function pwaCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        }

        window.pwaRequestNotifications = function() {
            if (!('Notification' in window)) return Promise.resolve('unsupported');
            return Notification.requestPermission();
        };

        function pwaUrlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
            const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) outputArray[i] = rawData.charCodeAt(i);
            return outputArray;
        }

        window.pwaRegisterPush = async function() {
            if (!window.pwaConfig?.pushEnabled || !window.pwaConfig?.vapidPublicKey) return null;
            const subscription = await window.pwaSubscribePush(window.pwaConfig.vapidPublicKey);
            if (!subscription) return null;

            const response = await fetch(window.pwaConfig.routes.subscribe, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': pwaCsrfToken() },
                body: JSON.stringify(subscription),
            });
            return response.ok ? subscription : null;
        };

        window.pwaUnregisterPush = async function() {
            const reg = await navigator.serviceWorker.ready;
            const sub = await reg.pushManager.getSubscription();
            if (!sub) return false;
            await sub.unsubscribe();
            await fetch(window.pwaConfig.routes.unsubscribe, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': pwaCsrfToken() },
                body: JSON.stringify({ endpoint: sub.endpoint }),
            });
            return true;
        };

        window.pwaSendTestPush = () => {
            return fetch(window.pwaConfig.routes.test, { 
                method: 'POST', 
                headers: { 'X-CSRF-TOKEN': pwaCsrfToken() } 
            });
        };

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js').then(reg => {
                    reg.addEventListener('updatefound', () => {
                        const newWorker = reg.installing;
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                if (confirm(window.pwaConfig.lang.updateAvailable)) window.location.reload();
                            }
                        });
                    });
                });
            });
        }

        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', e => {
            e.preventDefault();
            deferredPrompt = e;
        });

        window.triggerPwaInstall = () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(() => { deferredPrompt = null; });
                return true;
            }
            return false;
        };

        window.pwaSubscribePush = async function(publicKey) {
            if (!('serviceWorker' in navigator) || !('PushManager' in window)) return null;
            const reg = await navigator.serviceWorker.ready;
            const permission = await window.pwaRequestNotifications();
            if (permission !== 'granted') return null;
            return await reg.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: pwaUrlBase64ToUint8Array(publicKey)
            });
        };

        function pwaClearBadge() {
            if ('clearAppBadge' in navigator) {
                navigator.clearAppBadge().catch(() => {});
            }
            if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
                navigator.serviceWorker.controller.postMessage({ type: 'CLEAR_BADGE' });
            }
        }

        window.addEventListener('focus', pwaClearBadge);

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                pwaClearBadge();
            }
        });
        </script>
HTML;

        return new HtmlString($html);
    }

    private function registerProfileTab(): void
    {

        EditProfile::registerCustomTabs(
            TabPosition::After,
            Tab::make('pwa')
                ->label(trans('pwa.profile.tab_label'))
                ->icon('heroicon-o-device-phone-mobile')
                ->schema([
                    Section::make(trans('pwa.profile.section_heading'))
                        ->description(trans('pwa.profile.section_description'))
                        ->schema([
                            PwaActions::make(),
                        ]),
                ])
        );
    }
}
