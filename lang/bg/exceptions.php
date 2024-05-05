<?php

return [
    'daemon_connection_failed' => 'Имаше изключение при опит за комуникация с daemon-а с код HTTP/:code. Това изключение бе записано.',
    'node' => [
        'servers_attached' => 'Този node не трябва да има сървъри на него за да се изтрие.',
        'daemon_off_config_updated' => 'Конфигурацията на daemon-а <strong>бе актуализирана</strong>, обаче възникна проблем при опит за автоматично актуализиране на конфигурационния файл на daemon-а. Ще трябва ръчно да актуализираш конфигорационния файл (config.yml) за да може daemon-а да приложи тези промени.',
    ],
    'allocations' => [
        'server_using' => 'В момента е назначен сървър към тази алокация. Алокацията може да се изтрие само когато не е назначен сървър към нея.',
        'too_many_ports' => 'Добавяне на над 1000 порта в единствен диапазон не се поддържа.',
        'invalid_mapping' => 'The mapping provided for :port was invalid and could not be processed.',
        'cidr_out_of_range' => 'CIDR нотацията позволява само маски между /25 и /32',
        'port_out_of_range' => 'Порт в алокация трябва да е по голям от 1024 и по малък или равен на 65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'Egg с назначени активни сървъри към него не може да се изтрие от панела.',
        'invalid_copy_id' => 'The Egg selected for copying a script from either does not exist, or is copying a script itself.',
        'has_children' => 'This Egg is a parent to one or more other Eggs. Please delete those Eggs before deleting this Egg.',
    ],
    'variables' => [
        'env_not_unique' => 'The environment variable :name must be unique to this Egg.',
        'reserved_name' => 'The environment variable :name is protected and cannot be assigned to a variable.',
        'bad_validation_rule' => 'The validation rule ":rule" is not a valid rule for this application.',
    ],
    'importer' => [
        'json_error' => 'There was an error while attempting to parse the JSON file: :error.',
        'file_error' => 'Даденият JSON файл не е валиден.',
        'invalid_json_provided' => 'Даденият JSON файл не е в разпознаем формат.',
    ],
    'subusers' => [
        'editing_self' => 'Редактирането на своя подпотребителски акаунт не е позволено.',
        'user_is_owner' => 'Не можеш да добавиш собственика на сървъра като подпотребител на този сървър.',
        'subuser_exists' => 'Потребител с този имейл адрес е вече подпотребител за този сървър.',
    ],
    'databases' => [
        'delete_has_databases' => 'Cannot delete a database host server that has active databases linked to it.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'The maximum interval time for a chained task is 15 minutes.',
    ],
    'locations' => [
        'has_nodes' => 'Не можеш да изтриеш локация с активни node-ове свързани към нея.',
    ],
    'users' => [
        'node_revocation_failed' => 'Failed to revoke keys on <a href=":link">Node #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'No nodes satisfying the requirements specified for automatic deployment could be found.',
        'no_viable_allocations' => 'No allocations satisfying the requirements for automatic deployment were found.',
    ],
    'api' => [
        'resource_not_found' => 'Поисканият ресурс не съществува на този сървър.',
    ],
];
