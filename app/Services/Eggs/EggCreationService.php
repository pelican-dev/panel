<?php

namespace App\Services\Eggs;

use Ramsey\Uuid\Uuid;
use App\Models\Egg;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use App\Exceptions\Service\Egg\NoParentConfigurationFoundException;

class EggCreationService
{
    /**
     * EggCreationService constructor.
     */
    public function __construct(private ConfigRepository $config)
    {
    }

    /**
     * Create a new egg.
     *
     * @throws \App\Exceptions\Model\DataValidationException
     * @throws \App\Exceptions\Service\Egg\NoParentConfigurationFoundException
     */
    public function handle(array $data): Egg
    {
        $data['config_from'] = array_get($data, 'config_from');
        if (!is_null($data['config_from'])) {
            $parentEgg = Egg::query()->find(array_get($data, 'config_from'));
            throw_unless($parentEgg, new NoParentConfigurationFoundException(trans('exceptions.egg.invalid_copy_id')));
        }

        return Egg::query()->create(array_merge($data, [
            'uuid' => Uuid::uuid4()->toString(),
            'author' => $this->config->get('panel.service.author'),
        ]));
    }
}
