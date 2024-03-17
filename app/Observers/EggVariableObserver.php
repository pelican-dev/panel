<?php

namespace App\Observers;

use App\Models\EggVariable;

class EggVariableObserver
{
    public function creating(EggVariable $variable): void
    {
        if (isset($variable->field_type)) {
            unset($variable->field_type);
        }
    }

    public function updating(EggVariable $variable): void
    {
        if (isset($variable->field_type)) {
            unset($variable->field_type);
        }
    }
}
