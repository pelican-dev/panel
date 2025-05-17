<?php

return [
    'nav_title' => 'Datenbank-Hosts',
    'model_label' => 'Datenbank-Host',
    'model_label_plural' => 'Datenbank-Hosts',
    'table' => [
        'database' => 'Datenbank',
        'name' => 'Name',
        'host' => 'Host',
        'port' => 'Port',
        'name_helper' => 'Wenn Sie dies leer lassen, wird automatisch ein zufälliger Name generiert',
        'username' => 'Benutzername',
        'password' => 'Passwort',
        'remote' => 'Verbindungen von',
        'remote_helper' => 'Von wo aus Verbindungen erlaubt sein sollen. Leer lassen, um Verbindungen von überall zu erlauben.',
        'max_connections' => 'Max. Verbindungen',
        'created_at' => 'Erstellt am',
        'connection_string' => 'JDBC-Verbindungszeichenfolge',
    ],
    'error' => 'Fehler bei der Verbindung zum Host',
    'host' => 'Host',
    'host_help' => 'Die IP-Adresse oder der Domain-Name, die verwendet werden soll, wenn versucht wird, von diesem Panel aus eine Verbindung zu diesem MySQL-Host herzustellen, um neue Datenbanken zu erstellen.',
    'port' => 'Port',
    'port_help' => 'Der Port, auf dem MySQL für diesen Host läuft.',
    'max_database' => 'Max. Datenbanken',
    'max_databases_help' => 'Die maximale Anzahl der Datenbanken, die auf diesem Host erstellt werden können. Wenn das Limit erreicht ist, können keine neuen Datenbanken auf diesem Host erstellt werden. Leer bedeutet unbegrenzt.',
    'display_name' => 'Anzeigename',
    'display_name_help' => 'Die IP-Adresse oder der Domain-Name, die dem Endnutzer angezeigt werden soll.',
    'username' => 'Benutzername',
    'username_help' => 'Der Benutzername eines Kontos, das über ausreichende Berechtigungen verfügt, um neue Benutzer und Datenbanken im System zu erstellen.',
    'password' => 'Passwort',
    'password_help' => 'Das Passwort für den Datenbankbenutzer.',
    'linked_nodes' => 'Verknüpfte Nodes',
    'linked_nodes_help' => 'Diese Einstellung wird nur standardmäßig auf diesen Datenbank-Host gesetzt, wenn eine Datenbank zu einem Server auf dem ausgewählten Node hinzugefügt wird.',
    'connection_error' => 'Fehler bei der Verbindung zum Datenbank-Host',
    'no_database_hosts' => 'Keine Datenbank-Hosts',
    'no_nodes' => 'Keine Nodes',
    'delete_help' => 'Datenbank-Host hat Datenbanken',
    'unlimited' => 'Unbegrenzt',
    'anywhere' => 'Überall',

    'rotate' => 'Rotieren',
    'rotate_password' => 'Passwort rotieren',
    'rotated' => 'Passwort rotiert',
    'rotate_error' => 'Passwort-Rotation fehlgeschlagen',
    'databases' => 'Datenbanken',

    'setup' => [
        'preparations' => 'Vorbereitungen',
        'database_setup' => 'Datenbank-Einrichtung',
        'panel_setup' => 'Panel-Einrichtung',

        'note' => 'Derzeit werden für Datenbank-Hosts nur MySQL/MariaDB-Datenbanken unterstützt!',
        'different_server' => 'Befinden sich das Panel und die Datenbank <i>nicht</i> auf demselben Server?',

        'database_user' => 'Datenbank-Benutzer',
        'cli_login' => 'Verwenden Sie <code>mysql -u root -p</code>, um auf die MySQL-CLI zuzugreifen.',
        'command_create_user' => 'Befehl zum Erstellen des Benutzers',
        'command_assign_permissions' => 'Befehl zum Zuweisen von Berechtigungen',
        'cli_exit' => 'Um die MySQL-CLI zu beenden, führen Sie <code>exit</code> aus.',
        'external_access' => 'Externer Zugriff',
        'allow_external_access' => '
                                    <p>Wahrscheinlich müssen Sie externen Zugriff auf diese MySQL-Instanz erlauben, damit Server sich mit ihr verbinden können.</p>
                                    <br>
                                    <p>Öffnen Sie dazu <code>my.cnf</code>, dessen Speicherort je nach Betriebssystem und MySQL-Installation variiert. Sie können <code>find /etc -iname my.cnf</code> eingeben, um es zu finden.</p>
                                    <br>
                                    <p>Öffnen Sie <code>my.cnf</code>, fügen Sie den folgenden Text am Ende der Datei hinzu und speichern Sie sie:<br>
                                    <code>[mysqld]<br>bind-address=0.0.0.0</code></p>
                                    <br>
                                    <p>Starten Sie MySQL/MariaDB neu, um diese Änderungen zu übernehmen. Dies überschreibt die Standard-MySQL-Konfiguration, die standardmäßig nur Anfragen von localhost akzeptiert. Durch diese Aktualisierung werden Verbindungen auf allen Schnittstellen und damit externe Verbindungen zugelassen. Stellen Sie sicher, dass Sie den MySQL-Port (Standard 3306) in Ihrer Firewall erlauben.</p>
                                ',
    ],
]; 