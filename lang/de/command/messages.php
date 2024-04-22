<?php

return [
    'user' => [
        'search_users' => 'Gib einen Benutzernamen, eine Benutzer-ID oder eine E-Mail Adresse ein',
        'select_search_user' => 'ID des zu löschenden Benutzers (Gib \'0\' ein, um erneut zu suchen)',
        'deleted' => 'Benutzerkonto erfolgreich aus dem Panel gelöscht.',
        'confirm_delete' => 'Bist du sicher, dass du dieses Benutzerkonto aus dem Panel löschen möchtest?',
        'no_users_found' => 'Für den angegebenen Suchbegriff wurden keine Benutzerkonten gefunden.',
        'multiple_found' => 'Mehrere Konten für den angegebenen Benutzer wurden gefunden, ein Benutzer konnte wegen der --no-interaction Flag nicht gelöscht werden.',
        'ask_admin' => 'Ist dieses Benutzerkonto ein Administratorkonto?',
        'ask_email' => 'E-Mail Adresse',
        'ask_username' => 'Benutzername',
        'ask_name_first' => 'Vorname',
        'ask_name_last' => 'Nachname',
        'ask_password' => 'Passwort',
        'ask_password_tip' => 'Wenn du ein Benutzerkonto mit einem zufälligen Passwort erstellen möchtest, führe diesen Befehl (CTRL+C) erneut aus und gebe die `--no-password` Flag an.',
        'ask_password_help' => 'Passwörter müssen mindestens 8 Zeichen lang sein und mindestens einen Großbuchstaben und eine Zahl enthalten.',
        '2fa_help_text' => [
            'Dieser Befehl wird die 2-Faktor-Authentifizierung für das Benutzerkonto deaktivieren, wenn sie aktiviert ist. Dies sollte nur als Wiederherstellungsbefehl verwendet werden, wenn der Benutzer aus seinem Konto ausgeschlossen ist.',
            'Wenn das nicht das ist, was Sie tun wollten, drücken Sie STRG+C, um diesen Prozess zu beenden.',
        ],
        '2fa_disabled' => '2-Faktor-Authentifizierung wurde für :email deaktiviert.',
    ],
    'schedule' => [
        'output_line' => 'Versenden des Auftrags für die erste Aufgabe in `:schedule` (:hash).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Service-Backup-Datei :file wird gelöscht.',
    ],
    'server' => [
        'rebuild_failed' => 'Rebuild Anfrage für ":name" (#:id) im Node ":node" fehlgeschlagen mit Fehler: :message',
        'reinstall' => [
            'failed' => 'Neustart der Anfrage für ":name" (#:id) im Node ":node" fehlgeschlagen mit Fehler: :message',
            'confirm' => 'Du bist dabei, eine Gruppe von Servern neu zu installieren. Möchtest du fortfahren?',
        ],
        'power' => [
            'confirm' => 'Du bist dabei, eine :action auf :count Servern auszuführen. Möchtest du fortfahren?',
            'action_failed' => 'Power-Aktion für ":name" (#:id) auf Node ":node" fehlgeschlagen mit Fehler: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP Host (z.B. smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP Port',
            'ask_smtp_username' => 'SMTP Benutzername',
            'ask_smtp_password' => 'SMTP Passwort',
            'ask_mailgun_domain' => 'Mailgun Domain',
            'ask_mailgun_endpoint' => 'Mailgun Endpunkt',
            'ask_mailgun_secret' => 'Mailgun Verschlüsselung',
            'ask_mandrill_secret' => 'Mandrill Secret',
            'ask_postmark_username' => 'Postmark API Schlüssel',
            'ask_driver' => 'Welcher Treiber soll für das Versenden von E-Mails verwendet werden?',
            'ask_mail_from' => 'E-Mail Adresse, von der die E-Mails stammen sollten',
            'ask_mail_name' => 'Name, von denen E-Mails erscheinen sollen',
            'ask_encryption' => 'Zu verwendende Verschlüsselungsmethode',
        ],
    ],
];
