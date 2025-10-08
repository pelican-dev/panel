<?php

return [
    'user' => [
        'search_users' => 'Gebe einen Benutzernamen, eine Benutzer-ID oder E-Mail-Adresse ein',
        'select_search_user' => 'ID des zu löschenden Benutzers (Gib \'0\' ein, um erneut zu suchen)',
        'deleted' => 'Benutzerkonto erfolgreich aus dem Panel gelöscht.',
        'confirm_delete' => 'Bist du sicher, dass du dieses Benutzerkonto aus dem Panel löschen möchtest?',
        'no_users_found' => 'Für den angegebenen Suchbegriff wurden keine Benutzerkonten gefunden.',
        'multiple_found' => 'Mehrere Konten für den angegebenen Benutzer wurden gefunden. Ein Benutzer konnte wegen der --no-interaction Flag nicht gelöscht werden.',
        'ask_admin' => 'Ist dieser Benutzer ein Administrator?',
        'ask_email' => 'E-Mail-Adresse',
        'ask_username' => 'Benutzername',
        'ask_password' => 'Passwort',
        'ask_password_tip' => 'Wenn du ein Benutzerkonto mit einem zufälligen Passwort erstellen möchtest, führe den Befehl (CTRL+C) erneut aus und gebe die `--no-password` Flag an.',
        'ask_password_help' => 'Passwörter müssen mindestens 8 Zeichen lang sein und mindestens einen Großbuchstaben und eine Zahl enthalten.',
        '2fa_help_text' => 'Dieser Befehl deaktiviert die 2-Faktor-Authentifizierung für das Konto eines Benutzers, sofern diese aktiviert ist. Er sollte nur als Befehl zur Kontowiederherstellung verwendet werden, wenn der Benutzer aus seinem Konto ausgesperrt ist. Wenn Sie dies nicht beabsichtigt haben, drücken Sie STRG+C, um diesen Vorgang zu beenden.',
        '2fa_disabled' => '2-Faktor-Authentifizierung wurde für :email deaktiviert.',
    ],
    'schedule' => [
        'output_line' => 'Versenden des Auftrags für die erste Aufgabe in `:schedule` (:id).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Service-Backup-Datei :file wird gelöscht.',
    ],
    'server' => [
        'rebuild_failed' => 'Rebuild-Anfrage für ":name“ (#:id) auf dem Node ":node“ fehlgeschlagen mit Fehler: :message',
        'reinstall' => [
            'failed' => 'Neuinstallationsanforderung für ":name“ (#:id) auf dem Node ":node“ fehlgeschlagen mit Fehler: :message',
            'confirm' => 'Du bist dabei, eine Neuinstallation für eine Gruppe von Servern durchzuführen. Möchtest Du fortfahren?',
        ],
        'power' => [
            'confirm' => 'Du bist dabei, die Aktion :action auf :count Servern auszuführen. Möchtest Du fortfahren?',
            'action_failed' => 'Power-Aktion für ":name" (#:id) auf Node ":node" fehlgeschlagen mit Fehler: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP-Host (z.B. smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP-Port',
            'ask_smtp_username' => 'SMTP-Benutzername',
            'ask_smtp_password' => 'SMTP-Passwort',
            'ask_mailgun_domain' => 'Mailgun-Domain',
            'ask_mailgun_endpoint' => 'Mailgun-Endpoint',
            'ask_mailgun_secret' => 'Mailgun-Secret',
            'ask_mandrill_secret' => 'Mandrill-Secret',
            'ask_postmark_username' => 'Postmark API Schlüssel',
            'ask_driver' => 'Welcher Treiber soll für das Versenden von E-Mails verwendet werden?',
            'ask_mail_from' => 'E-Mail Adresse, von der die E-Mails stammen sollen',
            'ask_mail_name' => 'Name, der bei versendeten E-Mails erscheinen soll',
            'ask_encryption' => 'Zu verwendende Verschlüsselungsmethode',
        ],
    ],
];
