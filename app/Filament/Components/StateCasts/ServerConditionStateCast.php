<?php

namespace App\Filament\Components\StateCasts;

use App\Enums\ContainerStatus;
use App\Enums\ServerState;
use BackedEnum;
use Filament\Schemas\Components\StateCasts\Contracts\StateCast;

class ServerConditionStateCast implements StateCast
{
    public function get(mixed $state): ?BackedEnum
    {
        if (blank($state)) {
            return null;
        }

        if ($state instanceof ServerState || $state instanceof ContainerStatus) {
            return $state;
        }

        $serverState = ServerState::tryFrom($state);
        if ($serverState) {
            return $serverState;
        }

        $containerStatus = ContainerStatus::tryFrom($state);
        if ($containerStatus) {
            return $containerStatus;
        }

        return null;
    }

    public function set(mixed $state): mixed
    {
        if (!($state instanceof BackedEnum)) {
            return $state;
        }

        return $state->value;
    }
}
