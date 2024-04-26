<?php

return [
    'notices' => [
        'imported' => 'Das Egg und die zugehörigen Variablen wurden erfolgreich importiert.',
        'updated_via_import' => 'Das Egg wurde mit der bereitgestellten Datei aktualisiert.',
        'deleted' => 'Das angeforderte Egg wurde erfolgreich aus dem Panel gelöscht.',
        'updated' => 'Egg Konfiguration wurde erfolgreich aktualisiert.',
        'script_updated' => 'Das Egg-Installationsskript wurde aktualisiert und wird bei der Installation von Servern ausgeführt.',
        'egg_created' => 'Ein neues Egg wurde erfolgreich erstellt.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'Die Variable ":variable" wurde gelöscht und wird nach einem Serverneustart nicht mehr verfügbar sein.',
            'variable_updated' => 'Die Variable ":variable" wurde aktualisiert. Du musst alle Server neustarten, die diese Variable verwenden, um die Änderungen zu übernehmen.',
            'variable_created' => 'Neue Variable wurde erfolgreich erstellt und diesem Egg zugewiesen.',
        ],
    ],
    'descriptions' => [
        'name' => 'Ein einfacher, menschenlesbarer Name, der als Identifikator für dieses Egg verwendet werden kann.',
        'description' => 'Eine Beschreibung des Eggs, welche bei Bedarf im gesamten Panel angezeigt wird.',
        'uuid' => 'This is the globally unique identifier for this Egg which Wings uses as an identifier.',
        'author' => 'The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.',
        'force_outgoing_ip' => "Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.\nRequired for certain games to work properly when the Node has multiple public IP addresses.\nEnabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node.",
        'startup' => 'Der Standard Startbefehl, der für neue Server mit diesem Egg, verwendet werden soll.',
        'docker_images' => 'Die Docker Images, welche diesem Egg zur Verfügung stehen.',
    ],
];
