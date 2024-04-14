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
        'token' => 'Zwei-Faktor-Herausforderung gelöst',
        'ip-blocked' => 'Blockierte Anfrage von nicht gelisteter IP-Adresse für :identifier',
        'sftp' => [
            'fail' => 'Fehler beim SFTP-Login',
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
        'reinstall' => 'Server wurde neu installiert',
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
            'download' => ':name Backup heruntergeladen',
            'delete' => ':name Backup gelöscht',
            'restore' => ':name Backup wiederherstellen (gelöschte Dateien: :truncate)',
            'restore-complete' => 'Wiederherstellung des :name Backups abgeschlossen',
            'restore-failed' => 'Wiederherstellung des :name Backups fehlgeschlagen',
            'start' => 'Ein neues Backup :name gestartet',
            'complete' => ':name Backup als abgeschlossen markiert',
            'fail' => ':name Backup als fehlgeschlagen markiert',
            'lock' => ':name Backup gesperrt',
            'unlock' => ':name Backup entsperrt',
        ],
        'database' => [
            'create' => 'Neue Datenbank :name erstellt',
            'rotate-password' => 'Passwort für Datenbank :name gedreht',
            'delete' => 'Datenbank :name gelöscht',
        ],
        'file' => [
            'compress_one' => 'Komprimiert :directory:file',
            'compress_other' => ':count Dateien in :directory komprimiert',
            'read' => 'Inhalt von :file angesehen',
            'copy' => 'Kopie von :file erstellt',
            'create-directory' => 'Verzeichnis :directory:name erstellt',
            'decompress' => 'Dekomprimiert :files in :directory',
            'delete_one' => ':directory:files.0 gelöscht',
            'delete_other' => ':count Dateien in :directory gelöscht',
            'download' => ':file heruntergeladen',
            'pull' => 'Remote-Datei von :url nach :directory heruntergeladen',
            'rename_one' => ':directory:files.0.from nach :directory:files.0.to umbenannt',
            'rename_other' => ':count Dateien in :directory umbenannt',
            'write' => 'Neuen Inhalt in :file geschrieben',
            'upload' => 'Datei upload hat angefangen',
            'uploaded' => ':directory:file hochgeladen',
        ],
        'sftp' => [
            'denied' => 'SFTP-Zugriff aufgrund von Berechtigungen gesperrt',
            'create_one' => ':files.0 erstellt',
            'create_other' => ':count neue Dateien erstellt',
            'write_one' => 'Inhalt von :files.0 geändert',
            'write_other' => 'Inhalt von :count Dateien geändert',
            'delete_one' => ':files.0 gelöscht',
            'delete_other' => ':count Dateien gelöscht',
            'create-directory_one' => ':files.0 Verzeichnis erstellt',
            'create-directory_other' => ':count Verzeichnisse erstellt',
            'rename_one' => ':files.0.from zu :files.0.to umbenannt',
            'rename_other' => ':count Dateien umbenannt oder verschoben',
        ],
        'allocation' => [
            'create' => ':allocation zum Server hinzugefügt',
            'notes' => 'Die Notizen für :allocation von ":old" auf ":new" aktualisiert',
            'primary' => ':allocation als primäre Server-Zuordnung festgelegt',
            'delete' => ':allocation gelöscht',
        ],
        'schedule' => [
            'create' => ':name Zeitplan erstellt',
            'update' => ':name Zeitplan aktualisiert',
            'execute' => 'Manuell ausgeführter :name Zeitplan',
            'delete' => ':name Zeitplan gelöscht',
        ],
        'task' => [
            'create' => 'Erstellte eine neue ":action"-Aufgabe für den :name Zeitplan',
            'update' => 'Aktualisierte die ":action" Aufgabe für den :name Zeitplan',
            'delete' => 'Aufgabe für den :name Zeitplan gelöscht',
        ],
        'settings' => [
            'rename' => 'Server von :old zu :new umbenannt',
            'description' => 'Serverbeschreibung von :old zu :new geändert',
        ],
        'startup' => [
            'edit' => 'Die :variable Variable von ":old" zu ":new" geändert',
            'image' => 'Das Docker-Bild für den Server von :old auf :new aktualisiert',
        ],
        'subuser' => [
            'create' => ':email als Unterbenutzer hinzugefügt',
            'update' => 'Die Unterbenutzer-Berechtigungen für :email aktualisiert',
            'delete' => ':email als Unterbenutzer entfernt',
        ],
    ],
];
