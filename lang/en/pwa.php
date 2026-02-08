<?php

return [
    'navigation' => [
        'label' => 'PWA',
    ],
    'settings' => [
        'title' => 'PWA Settings',
    ],
    'tabs' => [
        'manifest' => 'Manifest',
        'push' => 'Push Notifications',
        'actions' => 'Actions',
    ],
    'fields' => [
        'theme_color' => [
            'label' => 'Theme color',
            'helper' => 'Used by the manifest and browser UI.',
        ],
        'background_color' => [
            'label' => 'Background color',
            'helper' => 'Splash screen background color.',
        ],
        'start_url' => [
            'label' => 'Start URL',
            'helper' => 'Relative URL for the PWA start.',
        ],
        'cache_name' => [
            'label' => 'Cache name',
            'helper' => 'Used in the service worker cache.',
        ],
        'cache_version' => [
            'label' => 'Cache version',
        ],
        'manifest_icon_192' => [
            'label' => 'Manifest icon (192x192)',
            'helper' => 'Android requires PNG here for install icon.',
        ],
        'manifest_icon_512' => [
            'label' => 'Manifest icon (512x512)',
            'helper' => 'Android requires PNG here for install icon.',
        ],
        'apple_touch_icon' => [
            'label' => 'Apple touch icon (default)',
        ],
        'apple_touch_icon_152' => [
            'label' => 'Apple touch icon (152x152)',
        ],
        'apple_touch_icon_167' => [
            'label' => 'Apple touch icon (167x167)',
        ],
        'apple_touch_icon_180' => [
            'label' => 'Apple touch icon (180x180)',
        ],
        'push_enabled' => [
            'label' => 'Enable push notifications',
            'helper' => 'Requires VAPID keys and the Web Push library.',
        ],
        'push_send_on_db' => [
            'label' => 'Send push for panel notifications',
            'helper' => 'Sends push when a notification is stored in the database.',
        ],
        'push_send_on_mail' => [
            'label' => 'Send push for mail notifications',
            'helper' => 'Sends push for notifications that only use the mail channel.',
        ],
        'vapid_subject' => [
            'label' => 'VAPID subject',
            'helper' => 'Usually a mailto: or https: URL, e.g. mailto:admin@example.com',
        ],
        'vapid_public_key' => [
            'label' => 'VAPID public key',
        ],
        'vapid_private_key' => [
            'label' => 'VAPID private key',
        ],
        'default_notification_icon' => [
            'label' => 'Default notification icon',
            'helper' => 'Default icon for push notifications.',
        ],
        'default_notification_badge' => [
            'label' => 'Default notification badge',
            'helper' => 'Default badge for push notifications.',
        ],
    ],
    'actions' => [
        'install' => 'Install PWA',
        'request_notifications' => 'Request Notifications',
        'subscribe' => 'Subscribe Push',
        'unsubscribe' => 'Unsubscribe',
        'test_push' => 'Send Test Push',
        'save' => 'Save',
    ],
    'notifications' => [
        'saved' => 'PWA settings saved.',
        'subscribed' => 'Successfully subscribed to push notifications.',
        'unsubscribed' => 'Successfully unsubscribed.',
        'test_sent' => 'Test notification has been sent.',
    ],
    'errors' => [
        'table_missing' => 'Push subscriptions table is missing.',
        'unauthorized' => 'Unauthorized access.',
        'library_missing' => 'Web Push library not found.',
        'vapid_missing' => 'VAPID keys or subject are missing.',
        'no_subscription' => 'No subscription found for this browser.',
        'send_failed' => 'Failed to send notification.',
        'unsupported' => 'Installation is currently not possible. The app may already be installed or your browser does not meet the requirements.',
    ],
    'profile' => [
        'tab_label' => 'PWA',
        'section_heading' => 'PWA Actions',
        'section_description' => 'Manage your device connection and notifications.',
    ],
    'messages' => [
        'update_available' => 'A new version is available. Reload now?',
        'test_notification_body' => 'This is a test notification from your PWA.',
        'new_notification' => 'You have a new notification.',
    ],
    'manifest' => [
        'description' => 'The official app for our panel.',
        'shortcuts' => [
            'dashboard_name' => 'Dashboard',
            'dashboard_short' => 'Dashboard',
            'dashboard_description' => 'View your servers',
        ],
    ],
];
