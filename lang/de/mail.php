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
        'action' => 'Visit Server',
    ],

    'removed_from_server' => [
        'body' => 'You have been removed as a subuser for the following server.',
        'server_name' => 'Server Name: :name',
        'action' => 'Visit Panel',
    ],

    'server_installed' => [
        'body' => 'Your server has finished installing and is now ready for you to use.',
        'server_name' => 'Server Name: :name',
        'action' => 'Login and Begin Using',
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
