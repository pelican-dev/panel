<?php

namespace App\Extensions\BackupAdapter\Schemas;

use App\Extensions\BackupAdapter\BackupAdapterSchemaInterface;
use App\Traits\EnvironmentWriterTrait;
use Filament\Schemas\Components\Component;
use Illuminate\Support\Str;

abstract class BackupAdapterSchema implements BackupAdapterSchemaInterface
{
    use EnvironmentWriterTrait;

    public function getName(): string
    {
        return Str::title($this->getId());
    }

    /** @return array<mixed> */
    public function getConfiguration(): array
    {
        return config('backups.disks.' . $this->getId(), []);
    }

    /** @param array<mixed> $configuration */
    public function saveConfiguration(array $configuration): void
    {
        $this->writeToEnvironment($configuration);
    }

    /** @return Component[] */
    public function getConfigurationForm(): array
    {
        return [];
    }
}
