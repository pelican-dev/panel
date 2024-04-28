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
        'uuid' => 'Dies ist der einzigartige Identifikator für dieses Egg, welches Wings als Identifikator verwendet.',
        'author' => 'Der Ersteller dieses Eggs. Das Hochladen einer neuer Konfiguration von einem anderen Autor ändert dies.',
        'force_outgoing_ip' => "Erzwingt ausgehenden Netzwerkverkehr, seine Source IP auf die IP der primären Zuweisung des Servers zu ändern.\nWird benötigt damit bestimmte Spiele richtig funktionieren, wenn der Node mehrere öffentliche IP-Adressen hat.\nDas Aktivieren dieser Option deaktiviert das interne Netzwerk für alle Server, die dieses Egg verwenden, was dazu führt, dass sie intern auf keine anderen Server der selben Node zugreifen können.",
        'startup' => 'Der Standard Startbefehl, der für neue Server mit diesem Egg, verwendet werden soll.',
        'docker_images' => 'Die Docker Images, welche diesem Egg zur Verfügung stehen.',
    ],
];
