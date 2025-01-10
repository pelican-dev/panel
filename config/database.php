<?php

use App\Enums\DatabaseDriver;

$database = env('DB_DATABASE', DatabaseDriver::Sqlite->getDefaultOption('database'));
$datapasePath = database_path($database);
if (str($database)->startsWith('/')) {
    $databasePath = $database;
}

return [

    'default' => env('DB_CONNECTION', 'sqlite'),

    'connections' => [
        'sqlite' => [
            'driver' => DatabaseDriver::Sqlite->value,
            'url' => env('DB_URL', DatabaseDriver::Sqlite->getDefaultOption('url')),
            'database' => $datapasePath,
            'prefix' => DatabaseDriver::Sqlite->getDefaultOption('prefix'),
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', DatabaseDriver::Sqlite->getDefaultOption('foreign_key_constraints')),
        ],

        'mysql' => [
            'driver' => DatabaseDriver::Mysql->value,
            'url' => env('DB_URL', DatabaseDriver::Mysql->getDefaultOption('url')),
            'host' => env('DB_HOST', DatabaseDriver::Mysql->getDefaultOption('host')),
            'port' => env('DB_PORT', DatabaseDriver::Mysql->getDefaultOption('port')),
            'database' => env('DB_DATABASE', DatabaseDriver::Mysql->getDefaultOption('database')),
            'username' => env('DB_USERNAME', DatabaseDriver::Mysql->getDefaultOption('username')),
            'password' => env('DB_PASSWORD', DatabaseDriver::Mysql->getDefaultOption('password')),
            'unix_socket' => env('DB_SOCKET', DatabaseDriver::Mysql->getDefaultOption('unix_socket')),
            'charset' => env('DB_CHARSET', DatabaseDriver::Mysql->getDefaultOption('charset')),
            'collation' => env('DB_COLLATION', DatabaseDriver::Mysql->getDefaultOption('collation')),
            'prefix' => env('DB_PREFIX', DatabaseDriver::Mysql->getDefaultOption('prefix')),
            'prefix_indexes' => DatabaseDriver::Mysql->getDefaultOption('prefix_indexes'),
            'strict' => env('DB_STRICT_MODE', DatabaseDriver::Mysql->getDefaultOption('strict')),
            'engine' => DatabaseDriver::Mysql->getDefaultOption('engine'),
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
            'test_database' => DatabaseDriver::Mysql->getDefaultOption('test_database'),
        ],

        'mariadb' => [
            'driver' => DatabaseDriver::Mariadb->value,
            'url' => env('DB_URL', DatabaseDriver::Mariadb->getDefaultOption('url')),
            'host' => env('DB_HOST', DatabaseDriver::Mariadb->getDefaultOption('host')),
            'port' => env('DB_PORT', DatabaseDriver::Mariadb->getDefaultOption('port')),
            'database' => env('DB_DATABASE', DatabaseDriver::Mariadb->getDefaultOption('database')),
            'username' => env('DB_USERNAME', DatabaseDriver::Mariadb->getDefaultOption('username')),
            'password' => env('DB_PASSWORD', DatabaseDriver::Mariadb->getDefaultOption('password')),
            'unix_socket' => env('DB_SOCKET', DatabaseDriver::Mariadb->getDefaultOption('unix_socket')),
            'charset' => env('DB_CHARSET', DatabaseDriver::Mariadb->getDefaultOption('charset')),
            'collation' => env('DB_COLLATION', DatabaseDriver::Mariadb->getDefaultOption('collation')),
            'prefix' => env('DB_PREFIX', DatabaseDriver::Mariadb->getDefaultOption('prefix')),
            'prefix_indexes' => DatabaseDriver::Mariadb->getDefaultOption('prefix_indexes'),
            'strict' => env('DB_STRICT_MODE', DatabaseDriver::Mariadb->getDefaultOption('strict')),
            'engine' => DatabaseDriver::Mariadb->getDefaultOption('engine'),
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
            'test_database' => DatabaseDriver::Mariadb->getDefaultOption('test_database'),
        ],

        'pgsql' => [
            'driver' => DatabaseDriver::Postgresql->value,
            'url' => env('DB_URL', DatabaseDriver::Postgresql->getDefaultOption('url')),
            'host' => env('DB_HOST', DatabaseDriver::Postgresql->getDefaultOption('host')),
            'port' => env('DB_PORT', DatabaseDriver::Postgresql->getDefaultOption('port')),
            'database' => env('DB_DATABASE', DatabaseDriver::Postgresql->getDefaultOption('database')),
            'username' => env('DB_USERNAME', DatabaseDriver::Postgresql->getDefaultOption('username')),
            'password' => env('DB_PASSWORD', DatabaseDriver::Postgresql->getDefaultOption('password')),
            'charset' => env('DB_CHARSET', DatabaseDriver::Postgresql->getDefaultOption('charset')),
            'prefix' => env('DB_PREFIX', DatabaseDriver::Postgresql->getDefaultOption('prefix')),
            'prefix_indexes' => DatabaseDriver::Postgresql->getDefaultOption('prefix_indexes'),
            'sslmode' => DatabaseDriver::Postgresql->getDefaultOption('sslmode'),
            'test_database' => DatabaseDriver::Postgresql->getDefaultOption('test_database'),
        ],
    ],

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => false, // disable to preserve original behavior for existing applications
    ],

    'redis' => [
        'client' => env('REDIS_CLIENT', 'predis'),

        'default' => [
            'scheme' => env('REDIS_SCHEME', 'tcp'),
            'path' => env('REDIS_PATH', '/run/redis/redis.sock'),
            'host' => env('REDIS_HOST', 'localhost'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DATABASE', 0),
            'context' => extension_loaded('redis') && env('REDIS_CLIENT') === 'phpredis' ? [
                'stream' => array_filter([
                    'verify_peer' => env('REDIS_VERIFY_PEER', true),
                    'verify_peer_name' => env('REDIS_VERIFY_PEER_NAME', true),
                    'cafile' => env('REDIS_CAFILE'),
                    'local_cert' => env('REDIS_LOCAL_CERT'),
                    'local_pk' => env('REDIS_LOCAL_PK'),
                ]),
            ] : [],
        ],

        'sessions' => [
            'scheme' => env('REDIS_SCHEME', 'tcp'),
            'path' => env('REDIS_PATH', '/run/redis/redis.sock'),
            'host' => env('REDIS_HOST', 'localhost'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DATABASE_SESSIONS', 1),
            'context' => extension_loaded('redis') && env('REDIS_CLIENT') === 'phpredis' ? [
                'stream' => array_filter([
                    'verify_peer' => env('REDIS_VERIFY_PEER', true),
                    'verify_peer_name' => env('REDIS_VERIFY_PEER_NAME', true),
                    'cafile' => env('REDIS_CAFILE'),
                    'local_cert' => env('REDIS_LOCAL_CERT'),
                    'local_pk' => env('REDIS_LOCAL_PK'),
                ]),
            ] : [],
        ],
    ],

];
