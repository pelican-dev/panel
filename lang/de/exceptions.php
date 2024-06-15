<?php

return [
    'daemon_connection_failed' => 'Beim Versuch, mit dem Daemon zu kommunizieren, gab es einen Fehler, was zu einem HTTP/:code Antwortcode führte. Dieser Fehler wurde protokolliert.',
    'node' => [
        'servers_attached' => 'Ein Node darf keine Server haben, die mit ihm verknüpft sind, um gelöscht zu werden.',
        'daemon_off_config_updated' => 'Die Daemon Konfiguration <strong>wurde aktualisiert</strong>, jedoch gab es einen Fehler bei dem Versuch, die Konfigurationsdatei des Daemon automatisch zu aktualisieren. Du musst die Konfigurationsdatei (config.yml) manuell anpassen, damit die Änderungen übernommen werden.',
    ],
    'allocations' => [
        'server_using' => 'Derzeit ist ein Server dieser Zuweisung zugewiesen. Eine Zuordnung kann nur gelöscht werden, wenn derzeit kein Server zugewiesen ist.',
        'too_many_ports' => 'Das Hinzufügen von mehr als 1000 Ports in einem einzigen Bereich wird nicht unterstützt.',
        'invalid_mapping' => 'Das für :port angegebene Mapping war ungültig und konnte nicht verarbeitet werden.',
        'cidr_out_of_range' => 'CIDR-Notation erlaubt nur Masken zwischen /25 und /32.',
        'port_out_of_range' => 'Ports in einer Zuteilung müssen größer als 1024 und kleiner oder gleich 65535 sein.',
    ],
    'egg' => [
        'delete_has_servers' => 'Ein Egg mit aktiven Servern kann nicht aus dem Panel gelöscht werden.',
        'invalid_copy_id' => 'Das Egg, das für das Kopieren eines Skripts ausgewählt wurde, existiert entweder nicht oder kopiert ein Skript selbst.',
        'has_children' => 'Dieses Egg ist ein Eltern-Ei für ein oder mehreren anderen Eiern. Bitte löschen Sie diese Eier bevor Sie dieses Ei löschen.',
    ],
    'variables' => [
        'env_not_unique' => 'Die Umgebungsvariable :name muss für dieses Egg eindeutig sein.',
        'reserved_name' => 'Die Umgebungsvariable :name ist geschützt und kann nicht einer Variable zugewiesen werden.',
        'bad_validation_rule' => 'Die Validierungsregel ":rule" ist keine gültige Regel für diese Anwendung.',
    ],
    'importer' => [
        'json_error' => 'Beim Verarbeiten der JSON-Datei ist ein Fehler aufgetreten: :error.',
        'file_error' => 'Die angegebene JSON-Datei war ungültig.',
        'invalid_json_provided' => 'Die angegebene JSON-Datei ist nicht in einem Format, das erkannt werden kann.',
    ],
    'subusers' => [
        'editing_self' => 'Das Bearbeiten Ihres eigenen Unterbenutzerkontos ist nicht zulässig.',
        'user_is_owner' => 'Du kannst den Serverbesitzer nicht als Unterbenutzer für diesen Server hinzufügen.',
        'subuser_exists' => 'Ein Benutzer mit dieser E-Mail Adresse ist bereits als Unterbenutzer für diesen Server zugewiesen.',
    ],
    'databases' => [
        'delete_has_databases' => 'Ein Datenbank Host kann nicht gelöscht werden, der aktive Datenbanken enthält.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'Die maximale Intervallzeit einer verketteten Aufgabe beträgt 15 Minuten.',
    ],
    'locations' => [
        'has_nodes' => 'Ein Standort, der aktive Nodes hat, kann nicht gelöscht werden.',
    ],
    'users' => [
        'node_revocation_failed' => 'Fehler beim Widerrufen der Schlüssel auf <a href=":link">Node #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Es konnten keine Nodes gefunden werden, die die für den automatischen Einsatz angegebenen Anforderungen erfüllen.',
        'no_viable_allocations' => 'Es wurden keine Zuweisungen gefunden, die die Anforderungen für den automatischen Einsatz erfüllen.',
    ],
    'api' => [
        'resource_not_found' => 'Die angeforderte Ressource existiert nicht auf diesem Server.',
    ],
];
