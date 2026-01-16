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
        'password-reset' => 'Passwort zurückgesetzt',
        'checkpoint' => 'Zwei-Faktor-Authentifizierung angefordert',
        'recovery-token' => 'Zwei-Faktor-Wiederherstellungs-Token verwendet',
        'token' => '2FA Überprüfung abgeschlossen',
        'ip-blocked' => 'Blockierte Anfrage von nicht gelisteter IP-Adresse für <b>:identifier</b>',
        'sftp' => [
            'fail' => 'Fehlgeschlagener SFTP-Login',
        ],
    ],
    'user' => [
        'account' => [
            'username-changed' => 'Benutzername von <b>:old</b> zu <b>:new</b> geändert',
            'email-changed' => 'E-Mail von <b>:old</b> auf <b>:new</b> geändert',
            'password-changed' => 'Passwort geändert',
        ],
        'api-key' => [
            'create' => 'Neuer API-Schlüssel <b>:identifier</b> erstellt',
            'delete' => 'API-Schlüssel <b>:identifier</b> gelöscht',
        ],
        'ssh-key' => [
            'create' => 'SSH-Schlüssel <b>:fingerprint</b> zum Konto hinzugefügt',
            'delete' => 'SSH-Schlüssel <b>:fingerprint</b> aus dem Konto entfernt',
        ],
        'two-factor' => [
            'create' => 'Zwei-Faktor-Authentifizierung aktiviert',
            'delete' => 'Zwei-Faktor-Authentifizierung deaktiviert',
        ],
    ],
    'server' => [
        'console' => [
            'command' => '"<b>:command</b>" auf dem Server ausgeführt',
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
            'restore-complete' => 'Wiederherstellung von Backup <b>:name</b> abgeschlossen',
            'restore-failed' => 'Wiederherstellung des Backups <b>:name</b> fehlgeschlagen',
            'start' => 'Neues Backup <b>:name</b> gestartet',
            'complete' => 'Backup <b>:name</b> als Erfolgreich markiert',
            'fail' => 'Backup <b>:name</b> als fehlgeschlagen markiert',
            'lock' => 'Backup <b>:name</b> gesperrt',
            'unlock' => 'Backup <b>:name</b> entsperrt',
            'rename' => 'Sicherung umbenannt von "<b>:old_name</b>" in "<b>:new_name</b>"',
        ],
        'database' => [
            'create' => 'Datenbank <b>:name</b> erstellt',
            'rotate-password' => 'Passwort für Datenbank <b>:name</b> zurückgesetzt',
            'delete' => 'Datenbank <b>:name</b> gelöscht',
        ],
        'file' => [
            'compress' => '<b>:directory:files</b> komprimiert|<b>:count</b> Dateien in <b>:directory</b> komprimiert',
            'read' => 'Inhalt von <b>:file</b> angesehen',
            'copy' => 'Kopie von <b>:file</b> erstellt',
            'create-directory' => 'Verzeichnis <b>:directory:name</b> erstellt',
            'decompress' => '<b>:file</b> in <b>:directory</b> entpackt',
            'delete' => '<b>:directory:files</b> gelöscht|<b>:count</b> Dateien in <b>:directory</b> gelöscht',
            'download' => '<b>:file</b> heruntergeladen',
            'pull' => 'Datei von <b>:url</b> nach <b>:directory</b> heruntergeladen',
            'rename' => '<b>:from</b> wurde verschoben nach / umbenannt zu <b>:to</b>|<b>:count</b> Dateien wurden in <b>:directory</b> Umbenannt / Verschoben',
            'write' => 'Neuen Inhalt in <b>:file</b> geschrieben',
            'upload' => 'Dateiupload gestartet',
            'uploaded' => '<b>:directory:file</b> hochgeladen',
        ],
        'sftp' => [
            'denied' => 'SFTP-Zugriff aufgrund von fehlenden Berechtigungen blockiert',
            'create' => '<b>:files</b> erstellt|<b>:count</b> neue Dateien erstellt',
            'write' => 'Inhalt von <b>:files</b> geändert|Inhalt von <b>:count</b> Dateien geändert',
            'delete' => '<b>:files</b> gelöscht|<b>:count</b> Dateien gelöscht',
            'create-directory' => 'Verzeichnis <b>:files</b> erstellt|<b>:count</b> Verzeichnisse erstellt',
            'rename' => '<b>:from</b> in <b>:to</b> umbenannt|<b>:count</b> Dateien umbenannt oder verschoben',
        ],
        'allocation' => [
            'create' => '<b>:allocation</b> zum Server hinzugefügt',
            'notes' => 'Notizen für <b>:allocation</b> von "<b>:old</b>" auf "<b>:new</b>" aktualisiert',
            'primary' => '<b>:allocation</b> als primäre Port-Allokation festgelegt',
            'delete' => ' <b>:allocation</b> gelöscht',
        ],
        'schedule' => [
            'create' => 'Zeitplan <b>:name</b> erstellt',
            'update' => 'Zeitplan <b>:name</b> aktualisiert',
            'execute' => 'Zeitplan <b>:name</b> manuell ausgeführt',
            'delete' => 'Zeitplan <b>:name</b> gelöscht',
        ],
        'task' => [
            'create' => 'Neue Aufgabe "<b>:action</b>" für den Zeitplan <b>:name</b> erstellt',
            'update' => 'Aufgabe "<b>:action</b>" für den Zeitplan <b>:name</b> aktualisiert',
            'delete' => 'Aufgabe "<b>:action</b>" wurde aus dem Zeitplan <b>:name</b> gelöscht',
        ],
        'settings' => [
            'rename' => 'Server von "<b>:old</b>" zu "<b>:new</b>" umbenannt',
            'description' => 'Serverbeschreibung von "<b>:old</b>" zu "<b>:new</b>" geändert',
            'reinstall' => 'Server neuinstalliert',
        ],
        'startup' => [
            'edit' => 'Variable <b>:variable</b> von "<b>:old</b>" zu "<b>:new</b>" geändert',
            'image' => 'Docker-Image für den Server von <b>:old</b> auf <b>:new</b> geändert',
            'command' => 'Der Startbefehl für den Server wurde von <b>:old</b> zu <b>:new</b> geändert',
        ],
        'subuser' => [
            'create' => '<b>:email</b> als Unterbenutzer hinzugefügt',
            'update' => 'Unterbenutzer-Berechtigungen für <b>:email</b> aktualisiert',
            'delete' => 'Unterbenutzer <b>:email</b> entfernt',
        ],
        'crashed' => 'Server abgestürzt',
    ],
];
