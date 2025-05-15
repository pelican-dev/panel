<?php

namespace App\Extensions\Features;

use Filament\Actions\Action;

interface FeatureSchemaInterface
{
    /** @return string[] */
    public function getListeners(): array;

    public function getId(): string;

    public function getAction(): Action;
}
