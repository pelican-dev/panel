<?php


return [

    /* Not yet added to the panel
    "debug" => [
        "enable" => "Debug mode has been enabled",
        "disable" => "Debug mode has been disabled",
        "disable_option" => "Disable Debug mode",
        "enable_option" => "Disable Debug mode",
        "cancel_option" => "Cancel the Command",
    ],
    */
    "appsettings" => [
        "comment" => [
            "author" => "Provide the email address that eggs exported by this Panel should be from. This should be a valid email address.",
            "url" => "The application URL MUST begin with https:// or http:// depending on if you are using SSL or not. If you do not include the scheme your emails and other content will link to the wrong location.",
            "timezone" => "The timezone should match one of PHP\'s supported timezones. If you are unsure, please reference https://php.net/manual/en/timezones.php.",
            //"lang" => "Choose a language you want to use on your panel.",
            "settings_ui" => "Enable UI based settings editor?",
        ],
        /*
        "lang" => [
        "question" => "What language do you want to use?",
        ],
        */
        "redis" => [
            "note" => 'You\'ve selected the Redis driver for one or more options, please provide valid connection information below. In most cases you can use the defaults provided unless you have modified your setup.',
            "comment" => "By default a Redis server instance has no password as it is running locally and inaccessible to the outside world. If this is the case, simply hit enter without entering a value.",
        ],
        ],
    "database_settings" => [
        "DB_HOST_note" => 'It is highly recommended to not use "localhost" as your database host as we have seen frequent socket connection issues. If you want to use a local connection you should be using "127.0.0.1".',
        "DB_USERNAME_note" => "Using the root account for MySQL connections is not only highly frowned upon, it is also not allowed by this application. You\'ll need to have created a MySQL user for this software.",
        "DB_PASSWORD_note" => "It appears you already have a MySQL connection password defined, would you like to change it?",
        "DB_error_2" => "Your connection credentials have NOT been saved. You will need to provide valid connection information before proceeding.",
    ],

];