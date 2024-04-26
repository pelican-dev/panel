<?php

return [
    'notices' => [
        'imported' => 'Яйцо и его переменные успешно импортированы.',
        'updated_via_import' => 'Яйцо успешно обновлено из файла.',
        'deleted' => 'Яйцо успешно удалено из панели.',
        'updated' => 'Конфигурация яйца успешно изменена.',
        'script_updated' => 'Скрипт установки яйца был успешно обновлен и будет выполняться при установке серверов.',
        'egg_created' => 'Яйцо успешно создано. Для применения изменений перезагрузите Wings.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'Переменная ":variable" удалена, после пересборки серверов она более не будет им доступна.',
            'variable_updated' => 'Переменная ":variable" обновлена. Для применения изменений на серверах необходимо их пересобрать.',
            'variable_created' => 'Новая переменная создана и назначена яйцу успешно.',
        ],
    ],
    'descriptions' => [
        'name' => 'Простое, понятное человеку имя для использования в качестве идентификатора для этого яйца.',
        'description' => 'A description of this Egg that will be displayed throughout the Panel as needed.',
        'uuid' => 'This is the globally unique identifier for this Egg which Wings uses as an identifier.',
        'author' => 'The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.',
        'force_outgoing_ip' => "Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.\nRequired for certain games to work properly when the Node has multiple public IP addresses.\nEnabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node.",
        'startup' => 'The default startup command that should be used for new servers using this Egg.',
        'docker_images' => 'The docker images available to servers using this egg.',
    ],
];
