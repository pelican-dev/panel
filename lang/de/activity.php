<?php

/**
 * Enthält alle Übersetzungsstrings für verschiedene Aktivitätsprotokoll-
 * Ereignisse. Diese sollten mit dem Wert vor dem Doppelpunkt (:)
 * im Ereignisnamen gekennzeichnet sein. Wenn kein Doppelpunkt vorhanden ist,
 * sollten sie auf der obersten Ebene stehen.
 */
return [
    'auth' => [
        'fail' => 'Anmeldung fehlgeschlagen',
        'success' => 'Erfolgreich angemeldet',
        'password-reset' => 'Passwort zurückgesetzt',
        'checkpoint' => 'Zwei-Faktor-Authentifizierung angefordert',
        'recovery-token' => 'Zwei-Faktor-Wiederherstellungstoken verwendet',
        'token' => 'Zwei-Faktor-Herausforderung gelöst',
        'ip-blocked' => 'Anfrage von nicht aufgelisteter IP-Adresse für <b>:identifier</b> blockiert',
        'sftp' => [
            'fail' => 'SFTP-Anmeldung fehlgeschlagen',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'E-Mail von <b>:old</b> zu <b>:new</b> geändert',
            'password-changed' => 'Passwort geändert',
        ],
        'api-key' => [
            'create' => 'Neuen API-Schlüssel <b>:identifier</b> erstellt',
            'delete' => 'API-Schlüssel <b>:identifier</b> gelöscht',
        ],
        'ssh-key' => [
            'create' => 'SSH-Schlüssel <b>:fingerprint</b> zum Konto hinzugefügt',
            'delete' => 'SSH-Schlüssel <b>:fingerprint</b> vom Konto entfernt',
        ],
        'two-factor' => [
            'create' => 'Zwei-Faktor-Authentifizierung aktiviert',
            'delete' => 'Zwei-Faktor-Authentifizierung deaktiviert',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'Befehl "<b>:command</b>" auf dem Server ausgeführt',
        ],
        'power' => [
            'start' => 'Server gestartet',
            'stop' => 'Server gestoppt',
            'restart' => 'Server neu gestartet',
            'kill' => 'Serverprozess beendet',
        ],
        'backup' => [
            'download' => 'Backup <b>:name</b> heruntergeladen',
            'delete' => 'Backup <b>:name</b> gelöscht',
            'restore' => 'Backup <b>:name</b> wiederhergestellt (gelöschte Dateien: <b>:truncate</b>)',
            'restore-complete' => 'Wiederherstellung des Backups <b>:name</b> abgeschlossen',
            'restore-failed' => 'Wiederherstellung des Backups <b>:name</b> fehlgeschlagen',
            'start' => 'Neues Backup <b>:name</b> gestartet',
            'complete' => 'Backup <b>:name</b> als abgeschlossen markiert',
            'fail' => 'Backup <b>:name</b> als fehlgeschlagen markiert',
            'lock' => 'Backup <b>:name</b> gesperrt',
            'unlock' => 'Backup <b>:name</b> entsperrt',
        ],
        'database' => [
            'create' => 'Neue Datenbank <b>:name</b> erstellt',
            'rotate-password' => 'Passwort für Datenbank <b>:name</b> rotiert',
            'delete' => 'Datenbank <b>:name</b> gelöscht',
        ],
        'file' => [
            'compress' => '<b>:directory:files</b> komprimiert|<b>:count</b> Dateien in <b>:directory</b> komprimiert',
            'read' => 'Inhalt von <b>:file</b> angesehen',
            'copy' => 'Kopie von <b>:file</b> erstellt',
            'create-directory' => 'Verzeichnis <b>:directory:name</b> erstellt',
            'decompress' => '<b>:file</b> in <b>:directory</b> dekomprimiert',
            'delete' => '<b>:directory:files</b> gelöscht|<b>:count</b> Dateien in <b>:directory</b> gelöscht',
            'download' => '<b>:file</b> heruntergeladen',
            'pull' => 'Remote-Datei von <b>:url</b> nach <b>:directory</b> heruntergeladen',
            'rename' => '<b>:from</b> nach <b>:to</b> verschoben/umbenannt|<b>:count</b> Dateien in <b>:directory</b> verschoben/umbenannt',
            'write' => 'Neue Inhalte in <b>:file</b> geschrieben',
            'upload' => 'Datei-Upload gestartet',
            'uploaded' => '<b>:directory:file</b> hochgeladen',
        ],
        'sftp' => [
            'denied' => 'SFTP-Zugriff aufgrund von Berechtigungen blockiert',
            'create' => '<b>:files</b> erstellt|<b>:count</b> neue Dateien erstellt',
            'write' => 'Inhalt von <b>:files</b> geändert|Inhalt von <b>:count</b> Dateien geändert',
            'delete' => '<b>:files</b> gelöscht|<b>:count</b> Dateien gelöscht',
            'create-directory' => 'Verzeichnis <b>:files</b> erstellt|<b>:count</b> Verzeichnisse erstellt',
            'rename' => '<b>:from</b> zu <b>:to</b> umbenannt|<b>:count</b> Dateien umbenannt oder verschoben',
        ],
        'allocation' => [
            'create' => '<b>:allocation</b> zum Server hinzugefügt',
            'notes' => 'Notizen für <b>:allocation</b> von "<b>:old</b>" zu "<b>:new</b>" aktualisiert',
            'primary' => '<b>:allocation</b> als primäre Server-Allokation festgelegt',
            'delete' => 'Allokation <b>:allocation</b> gelöscht',
        ],
        'schedule' => [
            'create' => 'Zeitplan <b>:name</b> erstellt',
            'update' => 'Zeitplan <b>:name</b> aktualisiert',
            'execute' => 'Zeitplan <b>:name</b> manuell ausgeführt',
            'delete' => 'Zeitplan <b>:name</b> gelöscht',
        ],
        'task' => [
            'create' => 'Neue Aufgabe "<b>:action</b>" für Zeitplan <b>:name</b> erstellt',
            'update' => 'Aufgabe "<b>:action</b>" für Zeitplan <b>:name</b> aktualisiert',
            'delete' => 'Aufgabe "<b>:action</b>" für Zeitplan <b>:name</b> gelöscht',
        ],
        'settings' => [
            'rename' => 'Server von "<b>:old</b>" zu "<b>:new</b>" umbenannt',
            'description' => 'Serverbeschreibung von "<b>:old</b>" zu "<b>:new</b>" geändert',
            'reinstall' => 'Server neu installiert',
        ],
        'startup' => [
            'edit' => 'Variable <b>:variable</b> von "<b>:old</b>" zu "<b>:new</b>" geändert',
            'image' => 'Docker-Image für den Server von <b>:old</b> zu <b>:new</b> aktualisiert',
        ],
        'subuser' => [
            'create' => '<b>:email</b> als Unterbenutzer hinzugefügt',
            'update' => 'Unterbenutzer-Berechtigungen für <b>:email</b> aktualisiert',
            'delete' => '<b>:email</b> als Unterbenutzer entfernt',
        ],
        'crashed' => 'Server abgestürzt',
    ],
];
