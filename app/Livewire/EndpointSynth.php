<?php

namespace App\Livewire;

use App\Models\Objects\Endpoint;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;

class EndpointSynth extends Synth
{
    public static $key = 'endpoint';

    public static function match($target)
    {
        return $target instanceof Endpoint;
    }

    public function dehydrate($target)
    {
        return (string) $target;
    }

    public function hydrate($value)
    {
        return new Endpoint($value);
    }
}
