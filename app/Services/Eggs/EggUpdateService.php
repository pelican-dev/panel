<?php

namespace App\Services\Eggs;

use App\Models\Egg;
use App\Exceptions\Service\Egg\NoParentConfigurationFoundException;

class EggUpdateService
{
    /**
     * Update an egg.
     *
     * @throws \App\Exceptions\Model\DataValidationException
     * @throws \App\Exceptions\Repository\RecordNotFoundException
     * @throws \App\Exceptions\Service\Egg\NoParentConfigurationFoundException
     */
    public function handle(Egg $egg, array $data): void
    {
        $eggId = array_get($data, 'config_from');
        if ($eggId) {
            $results = Egg::query()
                ->where('nest_id', $egg->nest_id)
                ->where('id', $eggId)
                ->count();

            if ($results !== 1) {
                throw new NoParentConfigurationFoundException(trans('exceptions.egg.invalid_copy_id'));
            }
        }

        // TODO: Once the admin UI is done being reworked and this is exposed
        //  in said UI, remove this so that you can actually update the denylist.
        unset($data['file_denylist']);

        $egg->update($data);
    }
}
