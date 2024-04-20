<?php

return [
    'exceptions' => [
        'no_new_default_allocation' => 'Du versuchst die Standard-Zuweisung für diesen Server zu löschen, es gibt aber keine Fallback-Zuweisung.',
        'marked_as_failed' => 'Dieser Server wurde als fehlgeschlagen einer vorherigen Installation markiert. Der aktuelle Status kann in diesem Zustand nicht umgestellt werden.',
        'bad_variable' => 'Es gab einen Validierungsfehler mit der Variable :name',
        'daemon_exception' => 'Es gab einen Fehler beim Versuch mit dem Daemon zu kommunizieren, was zu einem HTTP/:code Antwortcode führte. Diese Ausnahme wurde protokolliert. (Anfrage-Id: :request_id)',
        'default_allocation_not_found' => 'Die angeforderte Standard-Zuweisung wurde in den Zuweisungen dieses Servers nicht gefunden.',
    ],
    'alerts' => [
        'startup_changed' => 'Die Start-Konfiguration für diesen Server wurde aktualisiert. Wenn das Egg dieses Servers geändert wurde, wird jetzt eine Neuinstallation durchgeführt.',
        'server_deleted' => 'Der Server wurde erfolgreich aus dem System gelöscht.',
        'server_created' => 'Server wurde erfolgreich im Panel erstellt. Bitte gib dem Daemon ein paar Minuten, um diesen Server zu installieren.',
        'build_updated' => 'Die Build-Details für diesen Server wurden aktualisiert. Einige Änderungen erfordern möglicherweise einen Neustart, um wirksam zu werden.',
        'suspension_toggled' => 'Serversperrung wurde auf :status gesetzt.',
        'rebuild_on_boot' => 'Dieser Server benötigt einen Container-Rebuild. Dieser wird beim nächsten Start des Servers durchgeführt.',
        'install_toggled' => 'Der Installationsstatus für diesen Server wurde umgestellt.',
        'server_reinstalled' => 'Dieser Server steht für eine Neuinstallation in der Warteschlange.',
        'details_updated' => 'Serverdetails wurden erfolgreich aktualisiert.',
        'docker_image_updated' => 'Das Standard-Docker-Image für diesen Server wurde erfolgreich geändert. Um diese Änderung zu übernehmen, muss ein Neustart durchgeführt werden.',
        'node_required' => 'Du musst mindestens eine Node konfiguriert haben, bevor Du einen Server zu diesem Panel hinzufügen kannst.',
        'transfer_nodes_required' => 'Du musst mindestens zwei Nodes konfiguriert haben, bevor Du Server übertragen kannst.',
        'transfer_started' => 'Server-Übertragung wurde gestartet.',
        'transfer_not_viable' => 'Die ausgewählte Node verfügt nicht über den benötigten Arbeitsspeicher oder Speicherplatz, um diesen Server unterzubringen.',
    ],
];
