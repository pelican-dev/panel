<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => 'Gib eine E-Mail-Adresse an, von der exportierte Eggs von diesem Panel stammen sollen. Dies sollte eine gültige E-Mail-Adresse sein.',
            'url' => 'Die URL der Anwendung MUSS mit https:// oder http:// beginnen, je nachdem, ob Du SSL verwendest oder nicht. Wenn Du dies nicht einbindest, werden Deine E-Mails und andere Inhalte auf eine falsche Seite linken.',
            'timezone' => 'Die Zeitzone sollte mit einer der unterstützten PHP-Zeitzonen übereinstimmen. Wenn Du Dir nicht sicher bist, schau unter folgendem Link nach https://php.net/manual/en/timezones.php.',
        ],
        'redis' => [
            'note' => 'Du hast den Redis-Treiber für eine oder mehrere Optionen ausgewählt, bitte gib unten gültige Verbindungsinformationen an. In den meisten Fällen kannst Du die vorgegebenen Standardwerte verwenden, es sei denn, Du hast in Deinem Setup etwas geändert.',
            'comment' => 'Standardmäßig hat eine Redis-Server-Instanz kein Passwort, da sie lokal läuft und für die Außenwelt nicht zugänglich ist. Wenn dies der Fall ist, drücke einfach Enter ohne einen Wert einzugeben.',
            'confirm' => 'Es scheint, dass :field bereits für Redis definiert ist. Möchtest Du es ändern?',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => 'Es wird davon abgeraten "localhost" als Datenbank-Host zu verwenden, da wir häufig Probleme mit den Socket-Verbindungen hatten. Wenn Du eine lokale Verbindung verwenden möchtest, solltest Du "127.0.0.1" verwenden.',
        'DB_USERNAME_note' => 'Die Verwendung des root-Kontos für MySQL-Verbindungen ist nicht nur sehr umstritten, sondern auch von dieser Anwendung nicht erlaubt. Du musst einen eigenen MySQL-Benutzer für diese Software erstellt haben.',
        'DB_PASSWORD_note' => 'Es scheint, als hättest Du bereits ein Passwort für die MySQL-Verbindung definiert, möchtest Du es ändern?',
        'DB_error_2' => 'Deine Verbindungsdaten wurden NICHT gespeichert. Du musst gültige Verbindungsdaten angeben, bevor Du fortfährst.',
        'go_back' => 'Zurück und erneut versuchen',
    ],
    'make_node' => [
        'name' => 'Gib ein Kürzel an, um diesen Node von anderen unterscheiden zu können',
        'description' => 'Gib eine Beschreibung ein, um diesen Node zu identifizieren',
        'scheme' => 'Bitte gib entweder https für SSL-Verbindungen oder http für Verbindungen die kein SSL verwenden an',
        'fqdn' => 'Gib einen Domänennamen ein (z.B. node.example.com), der für die Verbindung zum Daemon verwendet werden soll. Eine IP-Adresse darf nur verwendet werden, wenn Du kein SSL für diesen Node verwendest',
        'public' => 'Soll dieser Node öffentlich sein? Tipp: Wird ein Node auf privat gestellt, ist es nicht möglich zu diesem Node automatisch zu deployen.',
        'behind_proxy' => 'Ist dein FQDN hinter einem Proxy?',
        'maintenance_mode' => 'Soll der Wartungsmodus aktiviert werden?',
        'memory' => 'Gib die maximale Menge an Arbeitsspeicher an',
        'memory_overallocate' => 'Gib die Menge an zusätzlichem Arbeitsspeicher an, den Du zuteilen möchtest. -1 deaktiviert die Überprüfung, und 0 verhindert das Erstellen neuer Server.',
        'disk' => 'Gib die maximale Größe an Speicherplatz an',
        'disk_overallocate' => 'Gib die Menge an zusätzlichem Speicher an, den Du zuweisen möchtest. -1 deaktiviert die Überprüfung, und 0 verhindert das Erstellen eines neuen Servers.',
        'cpu' => 'Gib die maximale Menge an CPU an',
        'cpu_overallocate' => 'Gib die Menge an zusätzlicher CPU-Leistung an, die Du zuweisen möchtest. -1 deaktiviert die Überprüfung, und 0 verhindert das Erstellen eines neuen Servers.',
        'upload_size' => 'Gib die maximale Dateigröße für das Hochladen an',
        'daemonListen' => 'Gib den Port für den Daemon an',
        'daemonConnect' => 'Geben Sie den Verbindungs-Port des Daemons ein (kann identisch mit dem Listen-Port sein)',
        'daemonSFTP' => 'Gib den SFTP-Port für den Daemon an',
        'daemonSFTPAlias' => 'Gib den Daemon SFTP-Alias ein (kann leer sein)',
        'daemonBase' => 'Gib den Basisordner an',
        'success' => 'Neuer Node mit dem Namen :name wurde erfolgreich erstellt und hat die ID :id',
    ],
    'node_config' => [
        'error_not_exist' => 'Der ausgewählte Node existiert nicht.',
        'error_invalid_format' => 'Ungültiges Format angegeben. Gültige Optionen sind yaml und json.',
    ],
    'key_generate' => [
        'error_already_exist' => 'Es scheint, als ob Du bereits einen Anwendungsverschlüsselungsschlüssel konfiguriert hast. Mit diesem Prozess fortzufahren kann zu Datenbeschädigung bereits verschlüsselter Daten führen. FAHRE NICHT FORT, ES SEI DENN DU WEIßT WAS DU TUST!',
        'understand' => 'Mir sind die Folgen der Ausführung dieses Befehls bekannt und ich übernehme jede Verantwortung für den Verlust von verschlüsselten Daten.',
        'continue' => 'Bist du sicher, dass du fortfahren möchtest? Änderungen des Anwendungsschlüssels FÜHRT ZU DATENVERLUST.',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => 'Es gibt keine geplanten Aufgaben für Server, die ausgeführt werden müssen.',
            'error_message' => 'Ein Fehler trat beim Verarbeiten des Zeitplans auf: ',
        ],
    ],
    'upgrade' => [
        'integrity' => 'Dieser Befehl überprüft nicht die Integrität heruntergeladener Dateien. Bitte stelle sicher, dass Du der Downloadquelle vertrauen kannst, bevor Du fortfahrst. Wenn Du kein Archiv herunterladen möchtest, gib bitte an, dass Du die Option --skip-download verwenden möchtest oder antworte "no" auf die folgende Frage.',
        'source_url' => 'Downloadquelle (gesetzt mit --url=):',
        'php_version' => 'Der Aktualisierungs-Prozess kann nicht ausgeführt werden. Die Mindestversion von PHP ist 7.4.0. Du benutzt',
        'skipDownload' => 'Möchtest du die Archivdateien für die neueste Version herunterladen und entpacken?',
        'webserver_user' => 'Dein Webserver-Benutzer wurde als <fg=blue>[{:user}]:</> erkannt. Ist das richtig?',
        'name_webserver' => 'Bitte gib den Namen des Benutzers ein, der Deinen Webserverprozess ausführt. Dieser variiert von System zu System, ist aber in der Regel "www-data", "nginx" oder "apache".',
        'group_webserver' => 'Deine Webserver-Gruppe wurde als <fg=blue>[{:group}]:</> erkannt. Ist das richtig?',
        'group_webserver_question' => 'Bitte gib den Namen der Gruppe ein, die Deinen Webserverprozess ausführt. In der Regel ist diese identisch zum Namen des Webserver Benutzers.',
        'are_your_sure' => 'Bist Du sicher, dass Du den Aktualisierungsprozess für Dein Panel ausführen möchtest?',
        'terminated' => 'Aktualisierungsprozess durch den Benutzer beendet.',
        'success' => 'Panel wurde erfolgreich aktualisiert. Bitte stelle sicher, dass Du auch alle Daemon-Instanzen aktualisierst',

    ],
];
