<?php

namespace App\Traits\Controllers;

use JavaScript;

trait PlainJavascriptInjection
{
    /**
     * Injects statistics into javascript.
     */
    public function injectJavascript($data): void
    {
        \JavaScript::put($data);
    }
}
