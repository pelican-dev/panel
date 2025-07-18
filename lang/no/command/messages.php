<?php

return [
    'user' => [
        'search_users' => 'Skriv inn et brukernavn, bruker-ID eller e-postadresse',
        'select_search_user' => 'ID-en til brukeren som skal slettes (Skriv inn \'0\' for å søke på nytt)',
        'deleted' => 'Brukeren ble vellykket slettet fra panelet.',
        'confirm_delete' => 'Er du sikker på at du vil slette denne brukeren fra panelet?',
        'no_users_found' => 'Ingen brukere ble funnet for det angitte søket.',
        'multiple_found' => 'Flere kontoer ble funnet for den angitte brukeren. Kan ikke slette en bruker på grunn av --no-interaction-flagget.',
        'ask_admin' => 'Er denne brukeren en administrator?',
        'ask_email' => 'E-postadresse',
        'ask_username' => 'Brukernavn',
        'ask_password' => 'Passord',
        'ask_password_tip' => 'Hvis du vil opprette en konto med et tilfeldig passord sendt til brukeren på e-post, kjør denne kommandoen på nytt (CTRL+C) og legg til flagget `--no-password`.',
        'ask_password_help' => 'Passord må være minst 8 tegn lange og inneholde minst én stor bokstav og ett tall.',
        '2fa_help_text' => [
            'Denne kommandoen vil deaktivere tofaktorautentisering for en brukers konto hvis den er aktivert. Dette bør bare brukes som en kontogjenopprettingskommando hvis brukeren er låst ute av kontoen sin.',
            'Hvis dette ikke var det du ønsket å gjøre, trykk CTRL+C for å avslutte prosessen.',
        ],
        '2fa_disabled' => 'Tofaktorautentisering er deaktivert for :email.',
    ],
    'schedule' => [
        'output_line' => 'Starter jobb for første oppgave i `:schedule` (:id).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Sletter sikkerhetskopifil for tjeneste :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Gjenoppbyggingsforespørsel for ":name" (#:id) på node ":node" mislyktes med feil: :message',
        'reinstall' => [
            'failed' => 'Reinstallasjonsforespørsel for ":name" (#:id) på node ":node" mislyktes med feil: :message',
            'confirm' => 'Du er i ferd med å reinstallere en gruppe servere. Ønsker du å fortsette?',
        ],
        'power' => [
            'confirm' => 'Du er i ferd med å utføre en :action på :count servere. Ønsker du å fortsette?',
            'action_failed' => 'Strømhåndteringsforespørsel for ":name" (#:id) på node ":node" mislyktes med feil: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP-vert (f.eks. smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP-port',
            'ask_smtp_username' => 'SMTP-brukernavn',
            'ask_smtp_password' => 'SMTP-passord',
            'ask_mailgun_domain' => 'Mailgun-domene',
            'ask_mailgun_endpoint' => 'Mailgun-endepunkt',
            'ask_mailgun_secret' => 'Mailgun-hemmelig nøkkel',
            'ask_mandrill_secret' => 'Mandrill-hemmelig nøkkel',
            'ask_postmark_username' => 'Postmark API-nøkkel',
            'ask_driver' => 'Hvilken driver skal brukes for å sende e-poster?',
            'ask_mail_from' => 'E-postadresse som e-poster skal sendes fra',
            'ask_mail_name' => 'Navn som e-poster skal vises fra',
            'ask_encryption' => 'Krypteringsmetode som skal brukes',
        ],
    ],
];
