<?php

namespace App\Livewire;

use App\Models\Objects\Endpoint;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;
use Stringable;

class EndpointSynth extends Synth
{
    public static string $key = 'endpoint';

    public static function match(mixed $target): bool
    {
        return $target instanceof Endpoint;
    }

    public function dehydrate(Stringable $target): string
    {
        return (string) $target;
    }

    public function hydrate(mixed $value): ?Endpoint
    {
        if (!is_string($value) && !is_int($value)) {
            return null;
        }

        return new Endpoint($value);
    }
}
