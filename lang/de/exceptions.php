<?php

return [
    'daemon_connection_failed' => 'Bei der Kommunikation mit dem Daemon ist eine Ausnahme aufgetreten, die zu einem HTTP/:code-Antwortcode führte. Diese Ausnahme wurde protokolliert.',
    'node' => [
        'servers_attached' => 'Ein Node muss keine Server haben, die mit ihm verknüpft sind, um gelöscht zu werden.',
        'error_connecting' => 'Fehler bei der Verbindung zu :node',
        'daemon_off_config_updated' => 'Die Daemon-Konfiguration wurde <strong>aktualisiert</strong>, jedoch ist ein Fehler aufgetreten, während versucht wurde, die Konfigurationsdatei auf dem Daemon automatisch zu aktualisieren. Sie müssen die Konfigurationsdatei (config.yml) für den Daemon manuell aktualisieren, um diese Änderungen zu übernehmen.',
    ],
    'allocations' => [
        'server_using' => 'Ein Server ist derzeit dieser Allokation zugewiesen. Eine Allokation kann nur gelöscht werden, wenn kein Server derzeit zugewiesen ist.',
        'too_many_ports' => 'Das Hinzufügen von mehr als 1000 Ports in einem einzigen Bereich wird nicht unterstützt.',
        'invalid_mapping' => 'Die bereitgestellte Zuordnung für :port war ungültig und konnte nicht verarbeitet werden.',
        'cidr_out_of_range' => 'CIDR-Notation erlaubt nur Masken zwischen /25 und /32.',
        'port_out_of_range' => 'Ports in einer Allokation müssen größer oder gleich 1024 und kleiner oder gleich 65535 sein.',
    ],
    'egg' => [
        'delete_has_servers' => 'Ein Egg mit aktiven, angehängten Servern kann nicht aus dem Panel gelöscht werden.',
        'invalid_copy_id' => 'Das für das Kopieren eines Skripts ausgewählte Egg existiert entweder nicht oder kopiert selbst ein Skript.',
        'has_children' => 'Dieses Egg ist ein übergeordnetes Element für ein oder mehrere andere Eggs. Bitte löschen Sie diese Eggs, bevor Sie dieses Egg löschen.',
    ],
    'variables' => [
        'env_not_unique' => 'Die Umgebungsvariable :name muss für dieses Egg eindeutig sein.',
        'reserved_name' => 'Die Umgebungsvariable :name ist geschützt und kann keiner Variable zugewiesen werden.',
        'bad_validation_rule' => 'Die Validierungsregel ":rule" ist keine gültige Regel für diese Anwendung.',
    ],
    'importer' => [
        'json_error' => 'Beim Versuch, die JSON-Datei zu parsen, ist ein Fehler aufgetreten: :error.',
        'file_error' => 'Die bereitgestellte JSON-Datei war nicht gültig.',
        'invalid_json_provided' => 'Die bereitgestellte JSON-Datei hat kein erkennbares Format.',
    ],
    'subusers' => [
        'editing_self' => 'Das Bearbeiten Ihres eigenen Subuser-Kontos ist nicht gestattet.',
        'user_is_owner' => 'Sie können den Serverbesitzer nicht als Subuser für diesen Server hinzufügen.',
        'subuser_exists' => 'Ein Benutzer mit dieser E-Mail-Adresse ist bereits als Subuser für diesen Server zugewiesen.',
    ],
    'databases' => [
        'delete_has_databases' => 'Ein Datenbank-Host-Server mit aktiven, verknüpften Datenbanken kann nicht gelöscht werden.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'Die maximale Intervallzeit für eine verkettete Aufgabe beträgt 15 Minuten.',
    ],
    'locations' => [
        'has_nodes' => 'Ein Standort mit aktiven, angehängten Nodes kann nicht gelöscht werden.',
    ],
    'users' => [
        'is_self' => 'Sie können Ihr eigenes Benutzerkonto nicht löschen.',
        'has_servers' => 'Ein Benutzer mit aktiven, angehängten Servern kann nicht gelöscht werden. Bitte löschen Sie zuerst seine Server.',
        'node_revocation_failed' => 'Fehler beim Widerrufen der Schlüssel auf <a href=":link">Node #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Es wurden keine Nodes gefunden, die die Anforderungen für die automatische Bereitstellung erfüllen.',
        'no_viable_allocations' => 'Es wurden keine Allokationen gefunden, die die Anforderungen für die automatische Bereitstellung erfüllen.',
    ],
    'api' => [
        'resource_not_found' => 'Die angeforderte Ressource existiert nicht auf diesem Server.',
    ],
    'mount' => [
        'servers_attached' => 'Ein Mount muss keine angehängten Server haben, um gelöscht zu werden.',
    ],
    'server' => [
        'marked_as_failed' => 'Dieser Server hat seinen Installationsprozess noch nicht abgeschlossen, bitte versuchen Sie es später erneut.',
    ],
];
