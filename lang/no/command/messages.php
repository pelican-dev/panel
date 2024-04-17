<?php

return [
    'user' => [
        'search_users' => 'Skriv inn brukernavn, bruker-ID, eller epostadresse',
        'select_search_user' => 'ID for brukeren som skal slettes (Angi \'0\' for å søke på nytt)',
        'deleted' => 'Brukeren ble slettet fra panelet.',
        'confirm_delete' => 'Are you sure you want to delete this user from the Panel?',
        'no_users_found' => 'No users were found for the search term provided.',
        'multiple_found' => 'Multiple accounts were found for the user provided, unable to delete a user because of the --no-interaction flag.',
        'ask_admin' => 'Er denne brukeren en administrator?',
        'ask_email' => 'E-postadresse',
        'ask_username' => 'Brukernavn',
        'ask_name_first' => 'Fornavn',
        'ask_name_last' => 'Etternavn',
        'ask_password' => 'Passord',
        'ask_password_tip' => 'If you would like to create an account with a random password emailed to the user, re-run this command (CTRL+C) and pass the `--no-password` flag.',
        'ask_password_help' => 'Passwords must be at least 8 characters in length and contain at least one capital letter and number.',
        '2fa_help_text' => [
            'This command will disable 2-factor authentication for a user\'s account if it is enabled. This should only be used as an account recovery command if the user is locked out of their account.',
            'If this is not what you wanted to do, press CTRL+C to exit this process.',
        ],
        '2fa_disabled' => '2-Factor authentication has been disabled for :email.',
    ],
    'schedule' => [
        'output_line' => 'Dispatching job for first task in `:schedule` (:hash).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Deleting service backup file :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Rebuild request for ":name" (#:id) on node ":node" failed with error: :message',
        'reinstall' => [
            'failed' => 'Reinstall request for ":name" (#:id) on node ":node" failed with error: :message',
            'confirm' => 'You are about to reinstall against a group of servers. Do you wish to continue?',
        ],
        'power' => [
            'confirm' => 'You are about to perform a :action against :count servers. Do you wish to continue?',
            'action_failed' => 'Power action request for ":name" (#:id) on node ":node" failed with error: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP-vert (e.g. smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP-port',
            'ask_smtp_username' => 'SMTP brukernavn',
            'ask_smtp_password' => 'SMTP passord',
            'ask_mailgun_domain' => 'Mailgun domene',
            'ask_mailgun_endpoint' => 'Mailgun Endpoint',
            'ask_mailgun_secret' => 'Mailgun Secret',
            'ask_mandrill_secret' => 'Mandrill Secret',
            'ask_postmark_username' => 'Postmark API nøkkel',
            'ask_driver' => 'Hvilken driver skal brukes for å sende e-post?',
            'ask_mail_from' => 'E-postadresse som e-poster skal bruke som avsender',
            'ask_mail_name' => 'Navnet på e-posten, epostene skal komme fra',
            'ask_encryption' => 'Krypteringsmetode som skal brukes',
        ],
    ],
];
