<?php

namespace App\Services\Eggs;

use App\Models\Egg;
use App\Exceptions\Service\Egg\NoParentConfigurationFoundException;

class EggUpdateService
{
    /**
     * Update an egg.
     */
    public function handle(Egg $egg, array $data): void
    {
        $eggId = array_get($data, 'config_from');
        $copiedFromEgg = Egg::query()->find($eggId);

        throw_unless($copiedFromEgg, new NoParentConfigurationFoundException(trans('exceptions.egg.invalid_copy_id')));

        // TODO: Once the admin UI is done being reworked and this is exposed
        //  in said UI, remove this so that you can actually update the denylist.
        unset($data['file_denylist']);

        $egg->update($data);
    }
}
