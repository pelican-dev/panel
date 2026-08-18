<?php

namespace App\ValueObjects;

use App\Enums\EnvironmentCheckStatus;

final readonly class EnvironmentCheckResult
{
    public function __construct(
        public string $key,
        public string $label,
        public EnvironmentCheckStatus $status,
        public string $message,
        public ?string $remediation = null,
    ) {}

    public function failed(): bool
    {
        return $this->status->isFailure();
    }
}
