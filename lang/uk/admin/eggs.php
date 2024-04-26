<?php

return [
    'notices' => [
        'imported' => 'Яйце та його змінні успішно імпортовано.',
        'updated_via_import' => 'Яйце було оновлено з файлу.',
        'deleted' => 'Яйце було видалено з панелі.',
        'updated' => 'Налаштування яйця були успішно оновленні.',
        'script_updated' => 'Скрипт установки яйця був успішно оновлений і буде виконуватися під час встановлення серверів.',
        'egg_created' => 'Нове яйце було успішно створено. Щоб застосувати це нове яйце Вам знадобиться перезавантажити Wings.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'Змінна ":variable" була видалена і більше не є доступна для серверів після перезавантаження.',
            'variable_updated' => 'Змінна ":variable" була оновлена. Вам знадобиться перезавантажити будь-які сервери які використовують цю змінну щоб зміни ввійшли в силу.',
            'variable_created' => 'Нову змінну успішно створено та призначено цьому яйцю.',
        ],
    ],
    'descriptions' => [
        'name' => 'A simple, human-readable name to use as an identifier for this Egg.',
        'description' => 'A description of this Egg that will be displayed throughout the Panel as needed.',
        'uuid' => 'This is the globally unique identifier for this Egg which Wings uses as an identifier.',
        'author' => 'The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.',
        'force_outgoing_ip' => "Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.\nRequired for certain games to work properly when the Node has multiple public IP addresses.\nEnabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node.",
        'startup' => 'The default startup command that should be used for new servers using this Egg.',
        'docker_images' => 'The docker images available to servers using this egg.',
    ],
];
