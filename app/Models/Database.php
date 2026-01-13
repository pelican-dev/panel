<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Traits\HasValidation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PDOException;

/**
 * @property int $id
 * @property int $server_id
 * @property int $database_host_id
 * @property string $database
 * @property string $username
 * @property string $remote
 * @property string $password
 * @property ?int $max_connections
 * @property string $jdbc
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Server $server
 * @property DatabaseHost $host
 */
class Database extends Model implements Validatable
{
    use HasFactory;
    use HasValidation;

    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal. Also used as name for api key permissions.
     */
    public const RESOURCE_NAME = 'server_database';

    /**
     * The attributes excluded from the model's JSON form.
     */
    protected $hidden = ['password'];

    /**
     * Fields that are mass assignable.
     */
    protected $fillable = [
        'server_id', 'database_host_id', 'database', 'username', 'password', 'remote', 'max_connections',
    ];

    /** @var array<array-key, string[]> */
    public static array $validationRules = [
        'server_id' => ['required', 'numeric', 'exists:servers,id'],
        'database_host_id' => ['required', 'exists:database_hosts,id'],
        'database' => ['required', 'string', 'alpha_dash', 'between:3,48'],
        'username' => ['string', 'alpha_dash', 'between:3,100'],
        'max_connections' => ['nullable', 'integer'],
        'remote' => ['required', 'string', 'regex:/^[\w\-\/.%:]+$/'],
        'password' => ['string'],
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

    public function address(): string
    {
        return $this->host->name . ':' . $this->host->port;
    }

    protected function jdbc(): Attribute
    {
        return Attribute::make(
            get: fn () => 'jdbc:mysql://' . $this->username . ':' . urlencode($this->password) . '@' . $this->address() . '/' . $this->database,
        );
    }

    /**
     * @throws PDOException
     *
     * Run the provided statement against the database on a given connection.
     */
    private function run(string $statement): void
    {
        $this->host->buildConnection()->statement($statement);
    }

    /**
     * @throws PDOException
     *
     * Create a new database on a given connection.
     */
    public function createDatabase(): self
    {
        $this->run(sprintf('CREATE DATABASE IF NOT EXISTS `%s`', $this->database));

        return $this;
    }

    /**
     * @throws PDOException
     *
     * Create a new database user on a given connection.
     */
    public function createUser(): self
    {
        $args = [$this->username, $this->remote, $this->password];
        $command = 'CREATE USER `%s`@`%s` IDENTIFIED BY \'%s\'';

        if (!empty($this->max_connections)) {
            $args[] = $this->max_connections;
            $command .= ' WITH MAX_USER_CONNECTIONS %s';
        }

        $this->run(sprintf($command, ...$args));

        return $this;
    }

    /**
     * @throws PDOException
     *
     * Give a specific user access to a given database.
     */
    public function assignUserToDatabase(): self
    {
        $this->run(sprintf(
            'GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, ALTER, REFERENCES, INDEX, LOCK TABLES, CREATE ROUTINE, ALTER ROUTINE, EXECUTE, CREATE TEMPORARY TABLES, CREATE VIEW, SHOW VIEW, EVENT, TRIGGER ON `%s`.* TO `%s`@`%s`',
            $this->database,
            $this->username,
            $this->remote
        ));

        return $this;
    }

    /**
     * @throws PDOException
     *
     * Flush the privileges for a given connection.
     */
    public function flushPrivileges(): self
    {
        $this->run('FLUSH PRIVILEGES');

        return $this;
    }

    /**
     * @throws PDOException
     *
     * Drop a given database on a specific connection.
     */
    public function dropDatabase(): self
    {
        $this->run(sprintf('DROP DATABASE IF EXISTS `%s`', $this->database));

        return $this;
    }

    /**
     * @throws PDOException
     *
     * Drop a given user on a specific connection.
     */
    public function dropUser(): self
    {
        $this->run(sprintf('DROP USER IF EXISTS `%s`@`%s`', $this->username, $this->remote));

        return $this;
    }
}
