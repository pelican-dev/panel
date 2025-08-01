<?php

return [
    'nav_title' => 'Eggs',
    'model_label' => 'Egg',
    'model_label_plural' => 'Eggs',
    'tabs' => [
        'configuration' => 'Konfiguration',
        'process_management' => 'Prozessverwaltung',
        'egg_variables' => 'Egg Variablen',
        'install_script' => 'Installationsscript',
    ],
    'import' => [
        'file' => 'Datei',
        'url' => 'URL',
        'egg_help' => 'Dies sollte die unveränderte .json-Datei sein ( egg-minecraft.json )',
        'url_help' => 'URLs müssen direkt auf die unveränderte .json-Datei zeigen',
        'add_url' => 'Neue URL',
        'import_failed' => 'Import fehlgeschlagen',
        'import_success' => 'Import erfolgreich',
        'github' => 'Aus Github importieren',
        'refresh' => 'Aktualisieren',
    ],
    'in_use' => 'In Verwendung',
    'servers' => 'Server',
    'name' => 'Name',
    'egg_uuid' => 'Egg UUID',
    'egg_id' => 'Egg ID',
    'name_help' => 'Ein einfacher, lesbarer Name, der als Kennzeichnung für dieses Egg verwendet wird.',
    'author' => 'Author',
    'uuid_help' => 'Dies ist der einzigartige Identifikator für dieses Egg, welchen Wings als Identifikator verwendet.',
    'author_help' => 'Der Ersteller dieser Egg Version.',
    'author_help_edit' => 'Der Ersteller dieser Egg Version. Das Hochladen einer neuen Konfiguration von einem anderen Autor ändert diesen.',
    'description' => 'Beschreibung',
    'description_help' => 'Eine Beschreibung des Eggs, welche bei Bedarf im gesamten Panel angezeigt wird.',
    'startup' => 'Start Befehl',
    'startup_help' => 'Der Standard Start Befehl, der für neue Server mit diesem Egg verwendet werden soll.',
    'file_denylist' => 'Datei Verbotsliste',
    'file_denylist_help' => 'Eine Liste von Dateien, die der Endbenutzer nicht bearbeiten darf.',
    'features' => 'Features',
    'force_ip' => 'Ausgehende IP erzwingen',
    'force_ip_help' => 'Erzwingt ausgehenden Netzwerkverkehr, seine Source IP auf die IP der primären Zuweisung des Servers zu ändern (NAT).
Wird benötigt damit bestimmte Spiele richtig funktionieren, wenn der Node mehrere öffentliche IP-Adressen hat.
Das Aktivieren dieser Option deaktiviert das interne Netzwerk für alle Server, die dieses Egg verwenden, was dazu führt, dass sie nicht mehr auf andere interne Server auf dem selben Node zugreifen können.',
    'tags' => 'Tags',
    'update_url' => 'Update URL',
    'update_url_help' => 'URLs müssen direkt auf die RAW .json-Datei zeigen',
    'add_image' => 'Docker Image hinzufügen',
    'docker_images' => 'Docker Images',
    'docker_name' => 'Image Name',
    'docker_uri' => 'Image URI',
    'docker_help' => 'Die Docker-Images, die Servern mit diesem Egg zur Verfügung stehen',

    'stop_command' => 'Stopp Befehl',
    'stop_command_help' => 'Der Befehl, der an Serverprozesse gesendet werden soll, um sie ordnungsgemäß zu stoppen. Wenn ein SIGINT gesendet werden soll, gebe ^C ein.',
    'copy_from' => 'Kopiere Einstellungen von',
    'copy_from_help' => 'Wenn Du die Einstellungen eines anderen Eggs benutzen möchtest, wähle es aus dem Menü oben aus.',
    'none' => 'Keine',
    'start_config' => 'Start Konfiguration',
    'start_config_help' => 'Liste der Werte, nach denen der Daemon beim Booten eines Servers suchen soll, um einen erfolgreichen Start zu erkennen.',
    'config_files' => 'Konfigurationsdateien',
    'config_files_help' => 'Dies sollte eine JSON-Darstellung von Konfigurationsdateien sein, die geändert werden sollen und welche Teile von ihnen geändert werden sollen.',
    'log_config' => 'Log Konfiguration',
    'log_config_help' => 'Dies sollte eine JSON-Darstellung sein, um dem Daemon zu zeigen, wo Log-Dateien gespeichert werden und ob der Daemon benutzerdefinierte Logs erstellen soll oder nicht.',

    'environment_variable' => 'Env Variable',
    'default_value' => 'Standardwert',
    'user_permissions' => 'Benutzerberechtigungen',
    'viewable' => 'Sichtbar',
    'editable' => 'Bearbeitbar',
    'rules' => 'Regeln',
    'add_new_variable' => 'Neue Variable hinzufügen',

    'error_unique' => 'Eine Variable mit diesem Namen existiert bereits.',
    'error_required' => 'Das Feld für Env Variable ist erforderlich.',
    'error_reserved' => 'Diese Env Variable ist reserviert und kann nicht verwendet werden.',

    'script_from' => 'Skript von',
    'script_container' => 'Skript-Container',
    'script_entry' => 'Skript-Eintrag',
    'script_install' => 'Installationsscript',
    'no_eggs' => 'Keine Eggs',
    'no_servers' => 'Keine Server',
    'no_servers_help' => 'Diesem Egg sind keine Server zugeordnet.',

    'update' => 'Aktualisieren|Ausgewählte aktualisieren',
    'updated' => 'Egg aktualisiert|:count/:total Eggs aktualisiert',
    'updated_failed' => ':count fehlgeschlagen',
    'update_question' => 'Bist du dir sicher, dass du das Egg aktualisieren möchtest?|Bist du dir sicher, dass du die ausgewählten Eggs aktualisieren möchtest?',
    'update_description' => 'Wenn du Änderungen am Egg vorgenommen hast, werden diese überschieben!|Wenn du Änderungen an den Eggs vorgenommen hast, werden diese überschrieben',
    'no_updates' => 'Keine Aktualisierungen für die ausgewählten Eggs verfügbar',
];
