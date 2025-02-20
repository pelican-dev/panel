<?php

namespace App\Checks;

use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class DatabaseCheck extends Check
{
    protected ?string $connectionName = null;

    public function connectionName(string $connectionName): self
    {
        $this->connectionName = $connectionName;

        return $this;
    }

    public function run(): Result
    {
        $connectionName = $this->connectionName ?? $this->getDefaultConnectionName();

        $result = Result::make()->meta([
            'connection_name' => $connectionName,
        ]);

        try {
            DB::connection($connectionName)->getPdo();

            return $result->ok(trans('admin/health.results.database.ok'));
        } catch (Exception $exception) {
            return $result->failed(trans('admin/health.results.database.failed', ['error' => $exception->getMessage()]));
        }
    }

    protected function getDefaultConnectionName(): string
    {
        return config('database.default');
    }
}
