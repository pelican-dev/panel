<?php

namespace App\Extensions\Illuminate\Events\Contracts;

use Illuminate\Contracts\Events\Dispatcher;

interface SubscribesToEvents
{
    public function subscribe(Dispatcher $events): void;
}
