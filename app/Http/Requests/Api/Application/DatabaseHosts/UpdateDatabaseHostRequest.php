<?php

namespace App\Http\Requests\Api\Application\DatabaseHosts;

use App\Models\DatabaseHost;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateDatabaseHostRequest extends StoreDatabaseHostRequest
{
    /** @return array<string, string|array<string|\Stringable|ValidationRule>> */
    public function rules(?array $rules = null): array
    {
        /** @var DatabaseHost $databaseHost */
        $databaseHost = $this->route()->parameter('database_host');

        return $rules ?? DatabaseHost::getRulesForUpdate($databaseHost);
    }
}
