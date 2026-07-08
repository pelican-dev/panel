<?php

namespace App\Extensions\BackupAdapter\Schemas;

use App\Extensions\BackupAdapter\BackupAdapterSchemaInterface;
use Illuminate\Support\Str;

abstract class BackupAdapterSchema implements BackupAdapterSchemaInterface
{
    public function getName(): string
    {
        return Str::title($this->getId());
    }
}
