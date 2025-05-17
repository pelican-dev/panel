<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => 'Geben Sie die E-Mail-Adresse ein, von der die von diesem Panel exportierten Eier stammen sollen. Dies sollte eine gültige E-Mail-Adresse sein.',
            'url' => 'Die Anwendungs-URL MUSS mit https:// oder http:// beginnen, je nachdem, ob Sie SSL verwenden oder nicht. Wenn Sie das Schema nicht einbeziehen, werden Ihre E-Mails und andere Inhalte auf den falschen Ort verlinken.',
            'timezone' => "Die Zeitzone sollte einer der von PHP unterstützten Zeitzonen entsprechen. Wenn Sie sich nicht sicher sind, konsultieren Sie bitte https://php.net/manual/en/timezones.php.",
        ],
        'redis' => [
            'note' => 'Sie haben den Redis-Treiber für eine oder mehrere Optionen ausgewählt. Bitte geben Sie unten gültige Verbindungsinformationen ein. In den meisten Fällen können Sie die bereitgestellten Standardeinstellungen verwenden, es sei denn, Sie haben Ihr Setup geändert.',
            'comment' => 'Standardmäßig hat eine Redis-Server-Instanz den Benutzernamen "default" und kein Passwort, da sie lokal läuft und von außen nicht zugänglich ist. Wenn dies der Fall ist, drücken Sie einfach die Eingabetaste, ohne einen Wert einzugeben.',
            'confirm' => 'Es scheint, dass ein :field bereits für Redis definiert ist. Möchten Sie es ändern?',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => 'Es wird dringend empfohlen, "localhost" nicht als Datenbank-Host zu verwenden, da wir häufig Socket-Verbindungsprobleme gesehen haben. Wenn Sie eine lokale Verbindung verwenden möchten, sollten Sie "127.0.0.1" verwenden.',
        'DB_USERNAME_note' => "Die Verwendung des Root-Kontos für MySQL-Verbindungen ist nicht nur verpönt, sondern auch von dieser Anwendung nicht erlaubt. Sie müssen einen MySQL-Benutzer für diese Software erstellt haben.",
        'DB_PASSWORD_note' => 'Es scheint, dass Sie bereits ein MySQL-Verbindungspasswort definiert haben. Möchten Sie es ändern?',
        'DB_error_2' => 'Ihre Verbindungsdaten wurden NICHT gespeichert. Sie müssen gültige Verbindungsinformationen bereitstellen, bevor Sie fortfahren können.',
        'go_back' => 'Zurückgehen und erneut versuchen',
    ],
    'make_node' => [
        'name' => 'Geben Sie eine kurze Kennung ein, die diesen Knoten von anderen unterscheidet',
        'description' => 'Geben Sie eine Beschreibung zur Identifizierung des Knotens ein',
        'scheme' => 'Bitte geben Sie entweder https für SSL oder http für eine Nicht-SSL-Verbindung ein',
        'fqdn' => 'Geben Sie einen Domainnamen ein (z.B. node.example.com), der für die Verbindung mit dem Daemon verwendet werden soll. Eine IP-Adresse darf nur verwendet werden, wenn Sie für diesen Knoten kein SSL verwenden',
        'public' => 'Soll dieser Knoten öffentlich sein? Beachten Sie, dass Sie durch das Setzen eines Knotens auf privat die Möglichkeit zum automatischen Deployment auf diesen Knoten verweigern.',
        'behind_proxy' => 'Ist Ihre FQDN hinter einem Proxy?',
        'maintenance_mode' => 'Soll der Wartungsmodus aktiviert werden?',
        'memory' => 'Geben Sie die maximale Speichermenge ein',
        'memory_overallocate' => 'Geben Sie die zu überbelegende Speichermenge ein, -1 deaktiviert die Überprüfung und 0 verhindert das Erstellen neuer Server',
        'disk' => 'Geben Sie den maximalen Festplattenspeicher ein',
        'disk_overallocate' => 'Geben Sie die zu überbelegende Festplattenmenge ein, -1 deaktiviert die Überprüfung und 0 verhindert das Erstellen neuer Server',
        'cpu' => 'Geben Sie die maximale CPU-Menge ein',
        'cpu_overallocate' => 'Geben Sie die zu überbelegende CPU-Menge ein, -1 deaktiviert die Überprüfung und 0 verhindert das Erstellen neuer Server',
        'upload_size' => "Geben Sie die maximale Datei-Upload-Größe ein",
        'daemonListen' => 'Geben Sie den Daemon-Überwachungsport ein',
        'daemonSFTP' => 'Geben Sie den Daemon-SFTP-Überwachungsport ein',
        'daemonSFTPAlias' => 'Geben Sie den Daemon-SFTP-Alias ein (kann leer sein)',
        'daemonBase' => 'Geben Sie den Basisordner ein',
        'success' => 'Neuer Knoten mit dem Namen :name wurde erfolgreich erstellt und hat die ID :id',
    ],
    'node_config' => [
        'error_not_exist' => 'Der ausgewählte Knoten existiert nicht.',
        'error_invalid_format' => 'Ungültiges Format angegeben. Gültige Optionen sind yaml und json.',
    ],
    'key_generate' => [
        'error_already_exist' => 'Es scheint, dass Sie bereits einen Anwendungsverschlüsselungsschlüssel konfiguriert haben. Die Fortsetzung dieses Prozesses überschreibt diesen Schlüssel und verursacht Datenbeschädigung für alle vorhandenen verschlüsselten Daten. FÜHREN SIE DIESEN PROZESS NUR FORT, WENN SIE WISSEN, WAS SIE TUN.',
        'understand' => 'Ich verstehe die Konsequenzen der Ausführung dieses Befehls und übernehme die volle Verantwortung für den Verlust verschlüsselter Daten.',
        'continue' => 'Sind Sie sicher, dass Sie fortfahren möchten? Das Ändern des Anwendungsverschlüsselungsschlüssels WIRD ZUM DATENVERLUST FÜHREN.',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => 'Es gibt keine geplanten Aufgaben für Server, die ausgeführt werden müssen.',
            'error_message' => 'Bei der Verarbeitung des Zeitplans ist ein Fehler aufgetreten: ',
        ],
    ],
    'upgrade' => [
        'integrity' => 'Dieser Befehl überprüft nicht die Integrität heruntergeladener Assets. Bitte stellen Sie sicher, dass Sie der Download-Quelle vertrauen, bevor Sie fortfahren. Wenn Sie kein Archiv herunterladen möchten, geben Sie dies bitte mit dem Flag --skip-download an oder antworten Sie mit "nein" auf die folgende Frage.',
        'source_url' => 'Download-Quelle (festgelegt mit --url=):',
        'php_version' => 'Selbst-Upgrade-Prozess kann nicht ausgeführt werden. Die erforderliche Mindest-PHP-Version ist 7.4.0, Sie haben',
        'skipDownload' => 'Möchten Sie die Archivdateien für die neueste Version herunterladen und entpacken?',
        'webserver_user' => 'Ihr Webserver-Benutzer wurde als <fg=blue>[{:user}]:</> erkannt, ist das korrekt?',
        'name_webserver' => 'Bitte geben Sie den Namen des Benutzers ein, der Ihren Webserver-Prozess ausführt. Dies variiert von System zu System, ist aber im Allgemeinen "www-data", "nginx" oder "apache".',
        'group_webserver' => 'Ihre Webserver-Gruppe wurde als <fg=blue>[{:group}]:</> erkannt, ist das korrekt?',
        'group_webserver_question' => 'Bitte geben Sie den Namen der Gruppe ein, die Ihren Webserver-Prozess ausführt. Normalerweise ist dies derselbe wie Ihr Benutzer.',
        'are_your_sure' => 'Sind Sie sicher, dass Sie den Upgrade-Prozess für Ihr Panel ausführen möchten?',
        'terminated' => 'Upgrade-Prozess vom Benutzer beendet.',
        'success' => 'Panel wurde erfolgreich aktualisiert. Bitte stellen Sie sicher, dass Sie auch alle Daemon-Instanzen aktualisieren',
    ],
];
