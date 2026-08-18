<?php

namespace App\Enums;

enum EnvironmentCheckStatus: string
{
    case Passed = 'passed';
    case Warning = 'warning';
    case Failed = 'failed';

    public function isFailure(): bool
    {
        return $this === self::Failed;
    }
}
