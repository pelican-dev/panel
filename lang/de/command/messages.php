<?php

return [
    'user' => [
        'search_users' => 'Geben Sie einen Benutzernamen, eine Benutzer-ID oder eine E-Mail-Adresse ein',
        'select_search_user' => 'ID des zu löschenden Benutzers (Geben Sie \'0\' ein, um erneut zu suchen)',
        'deleted' => 'Benutzer wurde erfolgreich aus dem Panel gelöscht.',
        'confirm_delete' => 'Sind Sie sicher, dass Sie diesen Benutzer aus dem Panel löschen möchten?',
        'no_users_found' => 'Für den angegebenen Suchbegriff wurden keine Benutzer gefunden.',
        'multiple_found' => 'Für den angegebenen Benutzer wurden mehrere Konten gefunden, Benutzer konnte aufgrund des --no-interaction Flags nicht gelöscht werden.',
        'ask_admin' => 'Ist dieser Benutzer ein Administrator?',
        'ask_email' => 'E-Mail-Adresse',
        'ask_username' => 'Benutzername',
        'ask_password' => 'Passwort',
        'ask_password_tip' => 'Wenn Sie ein Konto mit einem zufälligen Passwort erstellen möchten, das dem Benutzer per E-Mail zugesendet wird, führen Sie diesen Befehl erneut aus (STRG+C) und übergeben Sie das Flag `--no-password`.',
        'ask_password_help' => 'Passwörter müssen mindestens 8 Zeichen lang sein und mindestens einen Großbuchstaben und eine Zahl enthalten.',
        '2fa_help_text' => [
            'Dieser Befehl deaktiviert die 2-Faktor-Authentifizierung für das Benutzerkonto, wenn sie aktiviert ist. Dies sollte nur als Kontowiederherstellungsbefehl verwendet werden, wenn der Benutzer aus seinem Konto ausgesperrt ist.',
            'Wenn Sie dies nicht tun wollten, drücken Sie STRG+C, um diesen Prozess zu beenden.',
        ],
        '2fa_disabled' => '2-Faktor-Authentifizierung wurde für :email deaktiviert.',
    ],
    'schedule' => [
        'output_line' => 'Sende Job für erste Aufgabe in `:schedule` (:id).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Lösche Service-Backup-Datei :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Rebuild-Anfrage für ":name" (#:id) auf Node ":node" fehlgeschlagen mit Fehler: :message',
        'reinstall' => [
            'failed' => 'Neuinstallationsanfrage für ":name" (#:id) auf Node ":node" fehlgeschlagen mit Fehler: :message',
            'confirm' => 'Sie sind dabei, eine Neuinstallation für eine Gruppe von Servern durchzuführen. Möchten Sie fortfahren?',
        ],
        'power' => [
            'confirm' => 'Sie sind dabei, eine :action für :count Server durchzuführen. Möchten Sie fortfahren?',
            'action_failed' => 'Power-Aktion-Anfrage für ":name" (#:id) auf Node ":node" fehlgeschlagen mit Fehler: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP-Host (z.B. smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP-Port',
            'ask_smtp_username' => 'SMTP-Benutzername',
            'ask_smtp_password' => 'SMTP-Passwort',
            'ask_mailgun_domain' => 'Mailgun-Domain',
            'ask_mailgun_endpoint' => 'Mailgun-Endpunkt',
            'ask_mailgun_secret' => 'Mailgun-Secret',
            'ask_mandrill_secret' => 'Mandrill-Secret',
            'ask_postmark_username' => 'Postmark API-Schlüssel',
            'ask_driver' => 'Welcher Treiber soll für das Senden von E-Mails verwendet werden?',
            'ask_mail_from' => 'E-Mail-Adresse, von der E-Mails gesendet werden sollen',
            'ask_mail_name' => 'Name, unter dem E-Mails erscheinen sollen',
            'ask_encryption' => 'Zu verwendende Verschlüsselungsmethode',
        ],
    ],
];
