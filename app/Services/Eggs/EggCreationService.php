<?php

namespace App\Services\Eggs;

use Ramsey\Uuid\Uuid;
use App\Models\Egg;
use App\Contracts\Repository\EggRepositoryInterface;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use App\Exceptions\Service\Egg\NoParentConfigurationFoundException;

class EggCreationService
{
    /**
     * EggCreationService constructor.
     */
    public function __construct(private ConfigRepository $config, private EggRepositoryInterface $repository)
    {
    }

    /**
     * Create a new service option and assign it to the given service.
     *
     * @throws \App\Exceptions\Model\DataValidationException
     * @throws \App\Exceptions\Service\Egg\NoParentConfigurationFoundException
     */
    public function handle(array $data): Egg
    {
        $data['config_from'] = array_get($data, 'config_from');
        if (!is_null($data['config_from'])) {
            $results = $this->repository->findCountWhere([
                ['nest_id', '=', array_get($data, 'nest_id')],
                ['id', '=', array_get($data, 'config_from')],
            ]);

            if ($results !== 1) {
                throw new NoParentConfigurationFoundException(trans('exceptions.nest.egg.must_be_child'));
            }
        }

        return $this->repository->create(array_merge($data, [
            'uuid' => Uuid::uuid4()->toString(),
            'author' => $this->config->get('panel.service.author'),
        ]), true, true);
    }
}
