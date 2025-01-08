<?php

namespace App\Models;

use BadMethodCallException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property int $server_id
 * @property int $database_host_id
 * @property string $database
 * @property string $username
 * @property string $remote
 * @property string $password
 * @property int $max_connections
 * @property string $jdbc
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \App\Models\Server $server
 * @property \App\Models\DatabaseHost $host
 */
class Database extends Model
{
    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal. Also used as name for api key permissions.
     */
    public const RESOURCE_NAME = 'server_database';

    public const DEFAULT_CONNECTION_NAME = 'dynamic';

    public const DATABASE_SETUP_CONNECTION_NAME = '_panel_setup_database';

    public const JDBC_DRIVER_MAPPING = [
        'mysql' => 'mysql',
        'mariadb' => 'mariadb',
        'pgsql' => 'postgresql',
    ];

    /**
     * The table associated with the model.
     */
    protected $table = 'databases';

    /**
     * The attributes excluded from the model's JSON form.
     */
    protected $hidden = ['password'];

    /**
     * Fields that are mass assignable.
     */
    protected $fillable = [
        'server_id',
        'database_host_id',
        'database',
        'username',
        'password',
        'remote',
        'max_connections',
    ];

    public static array $validationRules = [
        'server_id' => 'required|numeric|exists:servers,id',
        'database_host_id' => 'required|exists:database_hosts,id',
        'database' => 'required|string|alpha_dash|between:3,48',
        'username' => 'string|alpha_dash|between:3,100',
        'max_connections' => 'nullable|integer',
        'remote' => 'required|string|regex:/^[\w\-\/.%:]+$/',
        'password' => 'string',
    ];

    protected function casts(): array
    {
        return [
            'server_id' => 'integer',
            'database_host_id' => 'integer',
            'max_connections' => 'integer',
            'password' => 'encrypted',
        ];
    }

    public function getRouteKeyName(): string
    {
        return $this->getKeyName();
    }

    /**
     * Gets the host database server associated with a database.
     */
    public function host(): BelongsTo
    {
        return $this->belongsTo(DatabaseHost::class, 'database_host_id');
    }

    /**
     * Gets the server associated with a database.
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    protected function jdbc(): Attribute
    {
        return Attribute::make(
            get: fn () => 'jdbc:' . self::JDBC_DRIVER_MAPPING[$this->host->driver] . '://' . $this->username . ':' . urlencode($this->password) . '@' . $this->host->host . ':' . $this->host->port . '/' . $this->database,
        );
    }

    /**
     * Run the provided statement against the database on a given connection.
     */
    private function run(string $statement): bool
    {
        return DB::connection(self::DEFAULT_CONNECTION_NAME)->statement($statement);
    }

    /**
     * Setup a temporary database connection.
     * Needed for PostgreSQL connections.
     */
    private function setConfigDatabase(string $database): void
    {
        config()->set('database.connections.' . self::DATABASE_SETUP_CONNECTION_NAME, [
            'driver' => $this->host->driver,
            'host' => $this->host->host,
            'port' => $this->host->port,
            'database' => $database,
            'username' => $this->host->username,
            'password' => $this->host->password,
            'charset' => 'UTF8',
            'collation' => 'en_US.UTF-8',
            'strict' => true,
        ]);
    }

    /**
     * Create a new database on a given connection.
     */
    public function createDatabase(string $database): bool
    {
        switch ($this->host->driver) {
            case 'mysql':
            case 'mariadb':
                return $this->run(sprintf('CREATE DATABASE IF NOT EXISTS `%s`', $database));
            case 'pgsql':
                return $this->run(sprintf('CREATE DATABASE "%s"', $database));
        }

        return false;
    }

    /**
     * Create a new database user on a given connection.
     */
    public function createUser(string $database, string $username, string $remote, string $password, ?int $max_connections): bool
    {
        $args = [];
        $command = '';
        switch ($this->host->driver) {
            case 'mysql':
            case 'mariadb':
                $args = [$username, $remote, $password];
                $command = 'CREATE USER `%s`@`%s` IDENTIFIED BY \'%s\'';

                if (!empty($max_connections)) {
                    $args[] = $max_connections;
                    $command .= ' WITH MAX_USER_CONNECTIONS %s';
                }

                return $this->run(sprintf($command, ...$args));
            case 'pgsql':
                try {
                    $this->setConfigDatabase($database);
                    $args = [$username, $password];
                    $command = 'CREATE USER "%s" WITH PASSWORD \'%s\'';

                    if (!empty($max_connections)) {
                        $args[] = $max_connections;
                        $command .= ' CONNECTION LIMIT %s';
                    }

                    return DB::connection(self::DATABASE_SETUP_CONNECTION_NAME)->statement(sprintf($command, ...$args));
                } finally {
                    DB::disconnect(self::DATABASE_SETUP_CONNECTION_NAME);
                }
        }

        return false;
    }

    /**
     * Updates the user's password on a given connection.
     * Only implemented for PostgreSQL.
     */
    public function updateUserPassword(string $database, string $username, string $remote, string $password): bool
    {
        switch ($this->host->driver) {
            case 'pgsql':
                try {
                    $this->setConfigDatabase($database);

                    return DB::connection(self::DATABASE_SETUP_CONNECTION_NAME)->statement(sprintf('ALTER USER "%s" WITH PASSWORD \'%s\'', $username, $password));
                } finally {
                    DB::disconnect(self::DATABASE_SETUP_CONNECTION_NAME);
                }
            default:
                throw new BadMethodCallException('updateUserPassword only implemented for PostgreSQL');
        }
    }

    /**
     * Give a specific user access to a given database.
     */
    public function assignUserToDatabase(string $database, string $username, string $remote): bool
    {
        switch ($this->host->driver) {
            case 'mysql':
            case 'mariadb':
                return $this->run(sprintf(
                    'GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, ALTER, REFERENCES, INDEX, LOCK TABLES, CREATE ROUTINE, ALTER ROUTINE, EXECUTE, CREATE TEMPORARY TABLES, CREATE VIEW, SHOW VIEW, EVENT, TRIGGER ON `%s`.* TO `%s`@`%s`',
                    $database,
                    $username,
                    $remote
                ));
            case 'pgsql':
                try {
                    $this->setConfigDatabase($database);
                    $success = DB::connection(self::DATABASE_SETUP_CONNECTION_NAME)->statement(sprintf('REVOKE CONNECT ON DATABASE "%s" FROM public', $database));
                    $success = DB::connection(self::DATABASE_SETUP_CONNECTION_NAME)->statement(sprintf('GRANT CONNECT ON DATABASE "%s" TO "%s"', $database, $username));
                    $success = DB::connection(self::DATABASE_SETUP_CONNECTION_NAME)->statement('DROP SCHEMA public');
                    $success = $success && DB::connection(self::DATABASE_SETUP_CONNECTION_NAME)->statement(sprintf(
                        'CREATE SCHEMA AUTHORIZATION "%s"',
                        $username
                    ));

                    return $success;
                } finally {
                    DB::disconnect(self::DATABASE_SETUP_CONNECTION_NAME);
                }
        }

        return false;
    }

    /**
     * Flush the privileges for a given connection.
     */
    public function flush(): bool
    {
        switch ($this->host->driver) {
            case 'mysql':
            case 'mariadb':
                return $this->run('FLUSH PRIVILEGES');
        }

        return true;
    }

    /**
     * Drop a given database on a specific connection.
     */
    public function dropDatabase(string $database): bool
    {
        switch ($this->host->driver) {
            case 'mysql':
            case 'mariadb':
                return $this->run(sprintf('DROP DATABASE IF EXISTS `%s`', $database));
            case 'pgsql':
                $success = $this->run(sprintf('SELECT pg_terminate_backend(pg_stat_activity.pid) FROM pg_stat_activity WHERE pg_stat_activity.datname = \'%s\' AND pid <> pg_backend_pid()', $database));
                $success = $success && $this->run(sprintf('DROP DATABASE IF EXISTS "%s"', $database));

                return $success;
        }

        return false;
    }

    /**
     * Drop a given user on a specific connection.
     */
    public function dropUser(string $username, string $remote): bool
    {
        switch ($this->host->driver) {
            case 'mysql':
            case 'mariadb':
                return $this->run(sprintf('DROP USER IF EXISTS `%s`@`%s`', $username, $remote));
            case 'pgsql':
                return $this->run(sprintf('DROP USER IF EXISTS "%s"', $username));
        }

        return false;
    }
}
