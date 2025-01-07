<?php

return [
    'user' => [
        'search_users' => 'Indtast et brugernavn, bruger ID eller e-mailadresse',
        'select_search_user' => 'ID på brugeren der skal slettes (Indtast \'0\' for at søge igen)',
        'deleted' => 'Brugeren blev slettet fra panelet.',
        'confirm_delete' => 'Er du sikker på at du vil slette denne bruger fra panelet?',
        'no_users_found' => 'Ingen brugere blev fundet for det angivne søgeord.',
        'multiple_found' => 'Der blev fundet flere konti for den angivne bruger, det er ikke muligt at slette en bruger på grund af --no-interaction flaget.',
        'ask_admin' => 'Er denne bruger en administrator?',
        'ask_email' => 'E-mailadresse',
        'ask_username' => 'Brugernavn',
        'ask_password' => 'Adgangskode',
        'ask_password_tip' => 'Hvis du vil oprette en konto med en tilfældig adgangskode sendt til brugeren, skal du køre denne kommando igen (CTRL+C) og tilføje `--no-password` flaget.',
        'ask_password_help' => 'Adgangskoder skal være mindst 8 tegn og indeholde mindst et stort bogstav og et tal.',
        '2fa_help_text' => [
            'Denne kommando vil deaktivere 2-faktor godkendelse for en brugers konto, hvis det er aktiveret. Dette bør kun bruges som en konto recovery kommando, hvis brugeren er låst ude af deres konto.',
            'Hvis dette ikke er det du ønskede at gøre, tryk CTRL+C for at afslutte denne proces.',
        ],
        '2fa_disabled' => '2-Factor godkendelse er blevet deaktiveret for :email.',
    ],
    'schedule' => [
        'output_line' => 'Udsender job for første opgave i `:schedule` (:hash).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Sletter service backup fil :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Genopbygnings anmodning for ":name" (#:id) på node ":node" mislykkedes med fejl: :message',
        'reinstall' => [
            'failed' => 'Geninstallation anmodning for ":name" (#:id) på node ":node" mislykkedes med fejl: :message',
            'confirm' => 'Du er ved at geninstallere en gruppe servere. Ønsker du at fortsætte?',
        ],
        'power' => [
            'confirm' => 'Du er ved at udføre en :action mod :count servere. Ønsker du at fortsætte?',
            'action_failed' => 'Power handling anmodning for ":name" (#:id) på node ":node" mislykkedes med fejl: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP Host (f.eks. smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP Port',
            'ask_smtp_username' => 'SMTP Brugernavn',
            'ask_smtp_password' => 'SMTP Adgangskode',
            'ask_mailgun_domain' => 'Mailgun Domæne',
            'ask_mailgun_endpoint' => 'Mailgun Endpoint',
            'ask_mailgun_secret' => 'Mailgun Secret',
            'ask_mandrill_secret' => 'Mandrill Secret',
            'ask_postmark_username' => 'Postmark API nøgle',
            'ask_driver' => 'Hvilken driver skal bruges til at sende e-mails?',
            'ask_mail_from' => 'E-mail skal sendes fra',
            'ask_mail_name' => 'Navn som e-mails skal vises fra',
            'ask_encryption' => 'Krypterings metode der skal bruges',
        ],
    ],
];
