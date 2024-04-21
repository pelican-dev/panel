<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Fehler beim Anmelden',
        'success' => 'Angemeldet',
        'password-reset' => 'Passwort zurücksetzen',
        'reset-password' => 'Angefordertes Passwort zurücksetzen',
        'checkpoint' => 'Zwei-Faktor-Authentifizierung angefordert',
        'recovery-token' => 'Zwei-Faktor-Wiederherstellungs-Token verwendet',
        'token' => '2FA Überprüfung abgeschlossen',
        'ip-blocked' => 'Blockierte Anfrage von nicht gelisteter IP-Adresse für :identifier',
        'sftp' => [
            'fail' => 'Fehlgeschlagener SFTP Login',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'E-Mail von :old auf :new geändert',
            'password-changed' => 'Passwort geändert',
        ],
        'api-key' => [
            'create' => 'Neuer API-Schlüssel :identifier erstellt',
            'delete' => 'API-Schlüssel :identifier gelöscht',
        ],
        'ssh-key' => [
            'create' => 'SSH-Schlüssel :fingerprint zum Konto hinzugefügt',
            'delete' => 'SSH-Schlüssel :fingerprint aus dem Konto entfernt',
        ],
        'two-factor' => [
            'create' => 'Zwei-Faktor-Authentifizierung aktiviert',
            'delete' => 'Zwei-Faktor-Authentifizierung deaktiviert',
        ],
    ],
    'server' => [
        'reinstall' => 'Server neuinstalliert',
        'console' => [
            'command' => '":command" auf dem Server ausgeführt',
        ],
        'power' => [
            'start' => 'Server gestartet',
            'stop' => 'Server gestoppt',
            'restart' => 'Server neu gestartet',
            'kill' => 'Serverprozess beendet',
        ],
        'backup' => [
            'download' => 'Backup :name heruntergeladen',
            'delete' => 'Backup :name gelöscht',
            'restore' => 'Backup :name wiederhergestellt (gelöschte Dateien: :truncate)',
            'restore-complete' => 'Wiederherstellen des Backups :name abgeschlossen',
            'restore-failed' => 'Wiederherstellen des Backups :name fehlgeschlagen',
            'start' => 'Ein neues Backup :name gestartet',
            'complete' => 'Backup :name als abgeschlossen markiert',
            'fail' => 'Backup :name als fehlgeschlagen markiert',
            'lock' => 'Backup :name gesperrt',
            'unlock' => 'Backup :name entsperrt',
        ],
        'database' => [
            'create' => 'Datenbank :name erstellt',
            'rotate-password' => 'Passwort für Datenbank :name zurückgesetzt',
            'delete' => 'Datenbank :name gelöscht',
        ],
        'file' => [
            'compress_one' => ':directory:file komprimiert',
            'compress_other' => ':count Dateien in :directory komprimiert',
            'read' => 'Inhalt von :file angesehen',
            'copy' => 'Kopie von :file erstellt',
            'create-directory' => 'Verzeichnis :directory:name erstellt',
            'decompress' => ':files in :directory entpackt',
            'delete_one' => ':directory:files.0 gelöscht',
            'delete_other' => ':count Dateien in :directory gelöscht',
            'download' => ':file heruntergeladen',
            'pull' => 'Remote-Datei von :url nach :directory heruntergeladen',
            'rename_one' => ':directory:files.0.from nach :directory:files.0.to umbenannt',
            'rename_other' => ':count Dateien in :directory umbenannt',
            'write' => 'Neuen Inhalt in :file geschrieben',
            'upload' => 'Datei-Upload begonnen',
            'uploaded' => ':directory:file hochgeladen',
        ],
        'sftp' => [
            'denied' => 'SFTP-Zugriff aufgrund von fehlenden Berechtigungen blockiert',
            'create_one' => ':files.0 erstellt',
            'create_other' => ':count Dateien erstellt',
            'write_one' => 'Inhalt von :files.0 geändert',
            'write_other' => 'Inhalt von :count Dateien geändert',
            'delete_one' => ':files.0 gelöscht',
            'delete_other' => ':count Dateien gelöscht',
            'create-directory_one' => 'Verzeichnis :files.0 erstellt',
            'create-directory_other' => ':count Verzeichnisse erstellt',
            'rename_one' => ':files.0.from zu :files.0.to umbenannt',
            'rename_other' => ':count Dateien umbenannt oder verschoben',
        ],
        'allocation' => [
            'create' => ':allocation zum Server hinzugefügt',
            'notes' => 'Notizen für :allocation von ":old" auf ":new" aktualisiert',
            'primary' => ':allocation als primäre Server-Zuweisung festgelegt',
            'delete' => ':allocation gelöscht',
        ],
        'schedule' => [
            'create' => 'Zeitplan :name erstellt',
            'update' => 'Zeitplan :name aktualisiert',
            'execute' => 'Zeitplan :name manuell ausgeführt',
            'delete' => 'Zeitplan :name gelöscht',
        ],
        'task' => [
            'create' => 'Erstellte eine neue ":action"-Aufgabe für den :name Zeitplan',
            'update' => 'Aktualisierte die ":action" Aufgabe für den :name Zeitplan',
            'delete' => 'Aufgabe für den Zeitplan :name gelöscht',
        ],
        'settings' => [
            'rename' => 'Server von :old zu :new umbenannt',
            'description' => 'Serverbeschreibung von :old zu :new geändert',
        ],
        'startup' => [
            'edit' => 'Die Variable :variable von ":old" zu ":new" geändert',
            'image' => 'Das Docker-Image für den Server von :old auf :new aktualisiert',
        ],
        'subuser' => [
            'create' => ':email als Unterbenutzer hinzugefügt',
            'update' => 'Die Unterbenutzer-Berechtigungen für :email aktualisiert',
            'delete' => 'Unterbenutzer :email entfernt',
        ],
    ],
];
