<?php

return [
    'create_action' => ':action Database Host',
    'table' => [
        'name' => 'Name',
        'host' => 'Host',
        'port' => 'Port',
        'username' => 'Username',
    ],

    'edit' => [
        'host' => 'Host',
        'host_help' => 'The IP address or Domain name that should be used when attempting to connect to this MySQL host from this Panel to create new databases.',
        'port' => 'Port',
        'post_help' => 'The port that MySQL is running on for this host.',
        'max_database' => 'Max :databases',
        'max_databases_help' => 'The maximum number of databases that can be created on this host. If the limit is reached, no new databases can be created on this host. Blank is unlimited.',
        'display_name' => 'Display Name',
        'display_name_help' => 'A short identifier used to distinguish this location from others. Must be between 1 and 60 characters, for example, us.nyc.lvl3.',
        'username' => 'Username',
        'username_help' => 'The username of an account that has enough permissions to create new users and databases on the system.',
        'password' => 'Password',
        'password_help' => 'The password for the database user.',
        'linked_nodes' => 'Linked :nodes',
        'linked_nodes_help' => 'This setting only defaults to this :databasehost when adding a :database to a :server on the selected :node.',
        'connection_error' => 'Error connecting to :databasehost',
        'table' => [
            'name_helper' => 'Leaving this blank will auto generate a random name',
            'username' => 'Username',
            'password' => 'Password',
            'remote' => 'Connections From',
            'remote_helper' => 'Where connections should be allowed from. Leave blank to allow connections from anywhere.',
            'max_connections' => 'Max Connections',
            'created_at' => 'Created At',
            'connection_string' => 'JDBC Connection String',
        ],
    ],

];
