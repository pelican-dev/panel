<?php

namespace App\Http\Controllers;

use App\Services\Pwa\PwaSettingsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PwaController extends Controller
{
    public function __construct(
        private PwaSettingsRepository $settings,
    ) {}

    public function manifest(): JsonResponse
    {
        $appName = config('app.name', 'Pelican Panel');
        $themeColor = $this->settings->get('theme_color', '#0ea5e9');
        $backgroundColor = $this->settings->get('background_color', '#0f172a');
        $startUrl = $this->startUrl();

        $icon192 = $this->settings->get('manifest_icon_192', '/pelican-192.png');
        $icon512 = $this->settings->get('manifest_icon_512', '/pelican-512.png');

        $manifest = [
            'name' => $appName,
            'short_name' => $appName,
            'description' => trans('pwa.manifest.description'),
            'start_url' => $startUrl,
            'scope' => '/',
            'display' => 'standalone',
            'background_color' => $backgroundColor,
            'theme_color' => $themeColor,
            'orientation' => 'portrait-primary',
            'icons' => [
                [
                    'src' => $this->assetOrUrl($icon192),
                    'sizes' => '192x192',
                    'type' => $this->iconMime($icon192),
                    'purpose' => 'any maskable',
                ],
                [
                    'src' => $this->assetOrUrl($icon512),
                    'sizes' => '512x512',
                    'type' => $this->iconMime($icon512),
                    'purpose' => 'any maskable',
                ],
            ],
            'categories' => ['utilities', 'productivity'],
            'shortcuts' => [
                [
                    'name' => trans('pwa.manifest.shortcuts.dashboard_name'),
                    'short_name' => trans('pwa.manifest.shortcuts.dashboard_short'),
                    'description' => trans('pwa.manifest.shortcuts.dashboard_description'),
                    'url' => url('/app'),
                    'icons' => [
                        [
                            'src' => $this->assetOrUrl($icon192),
                            'sizes' => '192x192',
                        ],
                    ],
                ],
            ],
        ];

        return response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json');
    }

    public function serviceWorker(): Response
    {
        $cacheName = (string) $this->settings->get('cache_name', 'pelican-pwa-v1');
        $cacheVersion = (int) $this->settings->get('cache_version', 1);

        $swDefaultTitle = config('app.name', 'Pelican Panel');
        $swDefaultBody = trans('pwa.messages.new_notification');
        $swDefaultIcon = $this->settings->get('default_notification_icon', '/pelican.svg');

        $serviceWorker = <<<'JS'
const CACHE_NAME = '__CACHE_NAME__';
const CACHE_VERSION = __CACHE_VERSION__;
const DEFAULT_TITLE = '__DEFAULT_TITLE__';
const DEFAULT_BODY = '__DEFAULT_BODY__';
const DEFAULT_ICON = '__DEFAULT_ICON__';

let badgeCount = 0;

function updateBadge() {
    if ('setAppBadge' in navigator) {
        if (badgeCount > 0) {
            navigator.setAppBadge(badgeCount).catch(() => {});
        } else {
            navigator.clearAppBadge().catch(() => {});
        }
    }
}

self.addEventListener('install', (event) => {
    console.log('PWA: Service Worker installing');
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    console.log('PWA: Service Worker activating');
    event.waitUntil(self.clients.claim());
});

self.addEventListener('push', (event) => {
    let data = {};
    
    if (event.data) {
        try {
            data = event.data.json();
        } catch (e) {
            data = { title: DEFAULT_TITLE, body: event.data.text() };
        }
    }

    const title = data.title || DEFAULT_TITLE;
    const options = {
        body: data.body || DEFAULT_BODY,
        icon: data.icon || DEFAULT_ICON,
        badge: data.badge || DEFAULT_ICON,
        vibrate: [200, 100, 200],
        data: data.url || '/',
        actions: data.actions || [],
        tag: data.tag || 'default',
        requireInteraction: data.requireInteraction || false
    };

    event.waitUntil(
        self.registration.showNotification(title, options).then(() => {
            badgeCount++;
            updateBadge();
        })
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const urlToOpen = event.notification.data || '/';
    const targetUrl = new URL(urlToOpen, self.location.origin).href;

    badgeCount = Math.max(0, badgeCount - 1);
    updateBadge();

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then((windowClients) => {
                for (const client of windowClients) {
                    if (client.url === targetUrl && 'focus' in client) {
                        return client.focus();
                    }
                }
                if (clients.openWindow) {
                    return clients.openWindow(targetUrl);
                }
            })
    );
});

self.addEventListener('message', (event) => {
    if (event.data?.type === 'CLEAR_BADGE') {
        badgeCount = 0;
        updateBadge();
    }
});

self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-notifications') {
        event.waitUntil(syncNotifications());
    }
});

async function syncNotifications() {
    console.log('PWA: Background sync triggered');
}
JS;

        $serviceWorker = str_replace(
            ['__CACHE_NAME__', '__CACHE_VERSION__', '__DEFAULT_TITLE__', '__DEFAULT_BODY__', '__DEFAULT_ICON__'],
            [
                addslashes($cacheName),
                (string) $cacheVersion,
                addslashes($swDefaultTitle),
                addslashes($swDefaultBody),
                addslashes($swDefaultIcon),
            ],
            $serviceWorker
        );

        return response($serviceWorker)
            ->header('Content-Type', 'application/javascript')
            ->header('Service-Worker-Allowed', '/');
    }

    private function startUrl(): string
    {
        $value = (string) $this->settings->get('start_url', '/');

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return url($value);
    }

    private function assetOrUrl(string $value): string
    {
        if ($value === '') {
            return asset('pelican.svg');
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return asset(ltrim($value, '/'));
    }

    private function iconMime(string $value): string
    {
        $lower = strtolower($value);
        if (str_ends_with($lower, '.svg')) {
            return 'image/svg+xml';
        }

        return 'image/png';
    }
}
