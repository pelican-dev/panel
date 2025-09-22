<?php

return [
    'nav_title' => 'Database Hosts',
    'model_label' => 'Database Host',
    'model_label_plural' => 'Database Hosts',
    'table' => [
        'database' => 'Database',
        'name' => 'Name',
        'host' => 'Host',
        'port' => 'Port',
        'name_helper' => 'Leaving this blank will auto generate a random name',
        'username' => 'Username',
        'password' => 'Password',
        'remote' => 'Connections From',
        'remote_helper' => 'Where connections should be allowed from. Leave blank to allow connections from anywhere.',
        'max_connections' => 'Max Connections',
        'created_at' => 'Created At',
        'connection_string' => 'JDBC Connection String',
    ],
    'error' => 'Error connecting to host',
    'host' => 'Host',
    'host_help' => 'The IP address or Domain name that should be used when attempting to connect to this MySQL host from this Panel to create new databases.',
    'port' => 'Port',
    'port_help' => 'The port that MySQL is running on for this host.',
    'max_database' => 'Max Databases',
    'max_databases_help' => 'The maximum number of databases that can be created on this host. If the limit is reached, no new databases can be created on this host. Blank is unlimited.',
    'display_name' => 'Display Name',
    'display_name_help' => 'The IP address or Domain name that should be shown to the enduser.',
    'username' => 'Username',
    'username_help' => 'The username of an account that has enough permissions to create new users and databases on the system.',
    'password' => 'Password',
    'password_help' => 'The password for the database user.',
    'linked_nodes' => 'Linked Nodes',
    'linked_nodes_help' => 'This setting only defaults to this database host when adding a database to a server on the selected Node.',
    'connection_error' => 'Error connecting to database host',
    'no_database_hosts' => 'No Database Hosts',
    'no_nodes' => 'No Nodes',
    'delete_help' => 'Database Host Has Databases',
    'unlimited' => 'Unlimited',
    'anywhere' => 'Anywhere',

    'rotate' => 'Rotate',
    'rotate_password' => 'Rotate Password',
    'rotated' => 'Password Rotated',
    'rotate_error' => 'Password Rotation Failed',
    'databases' => 'Databases',

    'setup' => [
        'preparations' => 'Preparations',
        'database_setup' => 'Database Setup',
        'panel_setup' => 'Panel Setup',

        'note' => 'Currently, only MySQL/ MariaDB databases are supported for database hosts!',
        'different_server' => 'Are the panel and the database <i>not</i> on the same server?',

        'database_user' => 'Database User',
        'cli_login' => 'Use <code>mysql -u root -p</code> to access mysql cli.',
        'command_create_user' => 'Command to create the user',
        'command_assign_permissions' => 'Command to assign permissions',
        'cli_exit' => 'To exit mysql cli run <code>exit</code>.',
        'external_access' => 'External Access',
        'allow_external_access' => '
                                    <p>Chances are you\'ll need to allow external access to this MySQL instance in order to allow servers to connect to it.</p>
                                    <br>
                                    <p>To do this, open <code>my.cnf</code>, which varies in location depending on your OS and how MySQL was installed. You can type find <code>/etc -iname my.cnf</code> to locate it.</p>
                                    <br>
                                    <p>Open <code>my.cnf</code>, add text below to the bottom of the file and save it:<br>
                                    <code>[mysqld]<br>bind-address=0.0.0.0</code></p>
                                    <br>
                                    <p>Restart MySQL/ MariaDB to apply these changes. This will override the default MySQL configuration, which by default will only accept requests from localhost. Updating this will allow connections on all interfaces, and thus, external connections. Make sure to allow the MySQL port (default 3306) in your firewall.</p>
                                ',
    ],
];
