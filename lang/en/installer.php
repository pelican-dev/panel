<?php

return [
    'title' => 'Panel Installer',
    'requirements' => [
        'title' => 'Server Requirements',
        'sections' => [
            'version' => [
                'title' => 'PHP Version',
                'or_newer' => ':version or newer',
                'content' => 'Your PHP Version is :version.',
            ],
            'extensions' => [
                'title' => 'PHP Extensions',
                'good' => 'All needed PHP Extensions are installed.',
                'bad' => 'The following PHP Extensions are missing: :extensions',
            ],
            'permissions' => [
                'title' => 'Folder Permissions',
                'good' => 'All Folders have the correct permissions.',
                'bad' => 'The following Folders have wrong permissions: :folders',
            ],
        ],
        'exception' => 'Some requirements are missing',
    ],
    'environment' => [
        'title' => 'Environment',
        'fields' => [
            'app_name' => 'App Name',
            'app_name_help' => 'This will be the Name of your Panel.',
            'app_url' => 'App URL',
            'app_url_help' => 'This will be the URL you access your Panel from.',
            'account' => [
                'section' => 'Admin User',
                'email' => 'E-Mail',
                'username' => 'Username',
                'password' => 'Password',
            ],
        ],
    ],
    'database' => [
        'title' => 'Database',
        'driver' => 'Database Driver',
        'driver_help' => 'The driver used for the panel database. We recommend "SQLite".',
        'fields' => [
            'host' => 'Database Host',
            'host_help' => 'The host of your database. Make sure it is reachable.',
            'port' => 'Database Port',
            'port_help' => 'The port of your database.',
            'path' => 'Database Path',
            'path_help' => 'The path of your .sqlite file relative to the database folder.',
            'name' => 'Database Name',
            'name_help' => 'The name of the panel database.',
            'username' => 'Database Username',
            'username_help' => 'The name of your database user.',
            'password' => 'Database Password',
            'password_help' => 'The password of your database user. Can be empty.',
        ],
        'exceptions' => [
            'connection' => 'Database connection failed',
            'migration' => 'Migrations failed',
        ],
    ],
    'session' => [
        'title' => 'Session',
        'driver' => 'Session Driver',
        'driver_help' => 'The driver used for storing sessions. We recommend "Filesystem" or "Database".',
    ],
    'cache' => [
        'title' => 'Cache',
        'driver' => 'Cache Driver',
        'driver_help' => 'The driver used for caching. We recommend "Filesystem".',
        'fields' => [
            'host' => 'Redis Host',
            'host_help' => 'The host of your redis server. Make sure it is reachable.',
            'port' => 'Redis Port',
            'port_help' => 'The port of your redis server.',
            'username' => 'Redis Username',
            'username_help' => 'The name of your redis user. Can be empty',
            'password' => 'Redis Password',
            'password_help' => 'The password for your redis user. Can be empty.',
        ],
        'exception' => 'Redis connection failed',
    ],
    'queue' => [
        'title' => 'Queue',
        'driver' => 'Queue Driver',
        'driver_help' => 'The driver used for handling queues. We recommend "Database".',
        'fields' => [
            'done' => 'I have done both steps below.',
            'done_validation' => 'You need to do both steps before continuing!',
            'crontab' => 'Run the following command to set up your crontab. Note that <code>www-data</code> is your webserver user. On some systems this username might be different!',
            'service' => 'To setup the queue worker service you simply have to run the following command.',
        ],
    ],
    'exceptions' => [
        'write_env' => 'Could not write to .env file',
        'migration' => 'Could not run migrations',
        'create_user' => 'Could not create admin user',
    ],
    'next_step' => 'Next Step',
    'finish' => 'Finish',
];
