<?php

return [
    'user' => [
        'search_users' => 'Geben Sie einen Benutzernamen, eine Benutzer-ID oder eine E-Mail-Adresse ein',
        'select_search_user' => 'ID des zu löschenden Benutzers (Gib \'0\' ein, um erneut zu suchen)',
        'deleted' => 'Benutzer erfolgreich aus dem Panel gelöscht.',
        'confirm_delete' => 'Sind Sie sicher, dass Sie diesen Benutzer aus dem Panel löschen möchten?',
        'no_users_found' => 'Für den angegebenen Suchbegriff wurden keine Benutzer gefunden.',
        'multiple_found' => 'Mehrere Konten für den angegebenen Benutzer wurden gefunden, ein Benutzer konnte wegen der --no-interaction Flags nicht gelöscht werden.',
        'ask_admin' => 'Ist dieser Benutzer ein Administrator?',
        'ask_email' => 'E-Mail-Adresse',
        'ask_username' => 'Benutzername',
        'ask_name_first' => 'Vorname',
        'ask_name_last' => 'Nachname',
        'ask_password' => 'Passwort',
        'ask_password_tip' => 'Wenn du ein Benutzerkonto mit einem zufälligen Passwort erstellen möchtest, führe diesen Befehl (CTRL+C) erneut aus und übergebe die `--no-password` Flag.',
        'ask_password_help' => 'Passwörter müssen mindestens 8 Zeichen lang sein und mindestens einen Großbuchstaben und eine Zahl enthalten.',
        '2fa_help_text' => [
            'Dieser Befehl wird die 2-Faktor-Authentifizierung für das Benutzerkonto deaktivieren, wenn es aktiviert ist. Dies sollte nur als Wiederherstellungsbefehl verwendet werden, wenn der Benutzer aus seinem Konto ausgeschieden ist.',
            'Wenn das nicht das ist, was Du tun wolltest, drücke STRG+C, um diesen Prozess zu beenden.',
        ],
        '2fa_disabled' => '2-Faktor-Authentifizierung wurde für :email deaktiviert.',
    ],
    'schedule' => [
        'output_line' => 'Dispatching-Job für die erste Aufgabe in `:schedule` (:hash).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Service Backup Datei :file wird gelöscht.',
    ],
    'server' => [
        'rebuild_failed' => 'Rebuild Request für ":name" (#:id) im Node ":node" fehlgeschlagen mit Fehler: :message',
        'reinstall' => [
            'failed' => 'Neustart der Anfrage für ":name" (#:id) im Node ":node" fehlgeschlagen mit Fehler: :message',
            'confirm' => 'Sie sind dabei, gegen eine Gruppe von Servern neu zu installieren. Möchten Sie fortfahren?',
        ],
        'power' => [
            'confirm' => 'Sie sind dabei, eine :action gegen :count Server auszuführen. Möchten Sie fortfahren?',
            'action_failed' => 'Power-Aktion für ":name" (#:id) im Node ":node" fehlgeschlagen mit Fehler: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP Host (z.B. smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP-Port',
            'ask_smtp_username' => 'SMTP Benutzername',
            'ask_smtp_password' => 'SMTP Passwort',
            'ask_mailgun_domain' => 'Mailgun-Domain',
            'ask_mailgun_endpoint' => 'Mailgun Endpoint',
            'ask_mailgun_secret' => 'Mailgun-Geheimnis',
            'ask_mandrill_secret' => 'Mandrill-Geheimnis',
            'ask_postmark_username' => 'Postmark API Key',
            'ask_driver' => 'Welcher Treiber soll für das Versenden von E-Mails verwendet werden?',
            'ask_mail_from' => 'E-Mail-Adressen sollten von',
            'ask_mail_name' => 'Name, von denen E-Mails erscheinen sollen',
            'ask_encryption' => 'Zu verwendende Verschlüsselungsmethode',
        ],
    ],
];
