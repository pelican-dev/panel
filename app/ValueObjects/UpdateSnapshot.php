<?php

namespace App\ValueObjects;

final readonly class UpdateSnapshot
{
    public function __construct(
        public string $path,
        public string $rollbackGuide,
        public string $databaseGuidance,
    ) {}
}
