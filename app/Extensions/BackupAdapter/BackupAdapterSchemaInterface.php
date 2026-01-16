<?php

namespace App\Extensions\BackupAdapter;

use App\Models\Backup;
use App\Models\User;
use Filament\Schemas\Components\Component;

interface BackupAdapterSchemaInterface
{
    public function getId(): string;

    public function getName(): string;

    public function createBackup(Backup $backup): void;

    public function deleteBackup(Backup $backup): void;

    public function getDownloadLink(Backup $backup, User $user): string;

    /** @return array<mixed> */
    public function getConfiguration(): array;

    /** @param array<mixed> $configuration */
    public function saveConfiguration(array $configuration): void;

    /** @return Component[] */
    public function getConfigurationForm(): array;
}
