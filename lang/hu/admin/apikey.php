<?php

return [
    'title' => 'Alkalmazás API kulcsok',
    'empty' => 'Nincs API kulcs',
    'whitelist' => 'Fehér listás IPv4-címek',
    'whitelist_help' => 'Az API-kulcsok korlátozhatók, hogy csak bizonyos IPv4-címekről működjenek. Adjon meg minden címet egy új sorban.',
    'whitelist_placeholder' => 'Például: 127.0.0.1 vagy 192.168.1.1',
    'description' => 'Leírás',
    'description_help' => 'Egy rövid összefoglaló hogy mire van használva ez a kulcs',
    'nav_title' => 'API kulcs',
    'model_label' => 'Alkalmazás API kulcs',
    'model_label_plural' => 'Alkalmazás API kulcsok',
    'table' => [
        'key' => 'Kulcs',
        'description' => 'Leírás',
        'last_used' => 'Legutóbb használt',
        'created' => 'Létrehozva',
        'created_by' => 'Létrehozta',
        'never_used' => 'Sosem használt',
    ],
    'permissions' => [
        'none' => 'Nincs',
        'read' => 'Olvasás',
        'read_write' => 'Olvasás és írás',
    ],
];
