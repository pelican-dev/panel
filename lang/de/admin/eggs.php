<?php

return [
    'notices' => [
        'imported' => 'Das Egg und die zugehörigen Variablen wurden erfolgreich importiert.',
        'updated_via_import' => 'Dieses Egg wurde mit der angegebenen Datei aktualisiert.',
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
        'name' => 'Ein einfacher, von Menschen lesbarer Name, der als Kennung für dieses Egg verwendet wird.',
        'description' => 'Eine Beschreibung dieses Eggs, die bei Bedarf im gesamten Panel angezeigt wird.',
        'uuid' => 'Dies ist der Globale eindeutige Bezeichner für dieses Egg, den Wings als Identifikator verwendet.',
        'author' => 'Der Autor dieser Version des Eggs. Wenn Sie eine neue Egg-Konfiguration von einem anderen Autor hochladen, wird dies geändert.',
        'force_outgoing_ip' => "Erzwingt, dass der gesamte ausgehende Netzwerkverkehr seine Quell-IP per NAT auf die primäre Zuweisungs-IP des Servers setzt.\nErforderlich, damit bestimmte Spiele ordnungsgemäß funktionieren, wenn der Node über mehrere öffentliche IP-Adressen verfügt.\nDas Aktivieren dieser Option deaktiviert das interne Netzwerk für alle Server, die dieses Egg verwenden, wodurch sie nicht mehr intern auf andere Server auf demselben Node zugreifen können.",
        'startup' => 'Der Standard-Startbefehl, der für neue Server verwendet werden soll, die dieses Egg nutzen.',
        'docker_images' => 'Die für Server, die dieses Egg verwenden, verfügbaren Docker-Images.',
    ],
];
