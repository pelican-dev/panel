<?php

return [
    'greeting' => 'Hello :name!',

    'account_created' => [
        'body' => 'You are receiving this email because an account has been created for you on :app.',
        'username' => 'Username: :username',
        'email' => 'Email: :email',
        'action' => 'Setup Your Account',
    ],

    'added_to_server' => [
        'body' => 'You have been added as a subuser for the following server, allowing you certain control over the server.',
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

    'mail_tested' => [
        'subject' => 'Panel Test Message',
        'body' => 'This is a test of the Panel mail system. You\'re good to go!',
    ],
];
