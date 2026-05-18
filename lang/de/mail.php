<?php

return [
    'greeting' => 'Hello :name!',

    'account_created' => [
        'body' => 'Sie erhalten diese E-Mail, weil für Sie ein Konto erstellt wurde für :app.',
        'username' => 'Benutzername: :username',
        'email' => 'E-Mail: :email',
        'action' => 'Richte dein Konto ein',
    ],

    'added_to_server' => [
        'body' => 'Sie wurden als Unterbenutzer für den folgenden Server hinzugefügt, wodurch Sie bestimmte Kontrollrechte über den Server erhalten.',
        'server_name' => 'Server Name: :name',
        'action' => 'Server ansehen',
    ],

    'removed_from_server' => [
        'body' => 'Sie wurden als Unterbenutzer für folgende Server entfernt.',
        'server_name' => 'Server Name: :name',
        'action' => 'Panel ansehen',
    ],

    'server_installed' => [
        'body' => 'Die Installation für den Server wurde abgeschlossen und ist nun bereit.',
        'server_name' => 'Server Name: :name',
        'action' => 'Anmelden und loslegen',
    ],

    'backup_completed' => [
        'body_success' => 'The backup was created successfully.',
        'body_failed' => 'The backup creation failed.',
        'backup_name' => 'Backup Name: :name',
        'server_name' => 'Server Name: :name',
        'action' => 'View Backups',
    ],

    'mail_tested' => [
        'subject' => 'Panel Test Message',
        'body' => 'This is a test of the Panel mail system. You\'re good to go!',
    ],
];
