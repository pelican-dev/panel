<?php

namespace App\Extensions\Tasks;

use App\Models\Schedule;
use App\Models\Task;
use Filament\Schemas\Components\Component;

interface TaskSchemaInterface
{
    public function getId(): string;

    public function getName(): string;

    public function runTask(Task $task): void;

    public function canCreate(Schedule $schedule): bool;

    public function getDefaultPayload(): ?string;

    public function getPayloadLabel(): ?string;

    /** @return null|string|string[] */
    public function formatPayload(string $payload): null|string|array;

    /** @return Component[] */
    public function getPayloadForm(): array;
}
