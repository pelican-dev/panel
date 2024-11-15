<?php

namespace App\Services\Databases;

use App\Models\Database;
use App\Helpers\Utilities;
use Illuminate\Database\ConnectionInterface;
use App\Extensions\DynamicDatabaseConnection;

class DatabasePasswordService
{
    /**
     * DatabasePasswordService constructor.
     */
    public function __construct(
        private ConnectionInterface $connection,
        private DynamicDatabaseConnection $dynamic,
    ) {}

    /**
     * Updates a password for a given database.
     */
    public function handle(Database|int $database): string
    {
        if (is_int($database)) {
            $database = Database::query()->findOrFail($database);
        }

        $password = Utilities::randomStringWithSpecialCharacters(24);

        $this->connection->transaction(function () use ($database, $password) {
            $this->dynamic->set('dynamic', $database->database_host_id);

            $database->update([
                'password' => $password,
            ]);

            $database->dropUser($database->username, $database->remote);
            $database->createUser($database->username, $database->remote, $password, $database->max_connections);
            $database->assignUserToDatabase($database->database, $database->username, $database->remote);
            $database->flush();
        });

        return $password;
    }
}
