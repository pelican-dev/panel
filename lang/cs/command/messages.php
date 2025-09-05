<?php

return [
    'user' => [
        'search_users' => 'Zadejte uživatelské jméno, ID uživatele nebo e-mailovou adresu',
        'select_search_user' => 'ID uživatele k odstranění (Zadejte \'0\' k opětovnému vyhledávání)',
        'deleted' => 'Uživatel byl úspěšně odstraněn z panelu.',
        'confirm_delete' => 'Opravdu chcete odstranit tohoto uživatele z panelu?',
        'no_users_found' => 'Pro hledaný výraz nebyl nalezen žádný uživatel.',
        'multiple_found' => 'Pro uživatele bylo nalezeno více účtů, není možné odstranit uživatele z důvodu vlajky --no-interaction.',
        'ask_admin' => 'Je tento uživatel správcem?',
        'ask_email' => 'Emailová adresa',
        'ask_username' => 'Uživatelské jméno',
        'ask_password' => 'Heslo',
        'ask_password_tip' => 'Pokud chcete vytvořit účet s náhodným heslem zaslaným uživateli, spusťte znovu tento příkaz (CTRL+C) a přejděte do proměnné `--no-password`.',
        'ask_password_help' => 'Heslo musí mít délku nejméně 8 znaků a obsahovat alespoň jedno velké písmeno a číslo.',
        '2fa_help_text' => [
            'Tento příkaz zakáže dvoufázové ověření pro uživatelský účet, pokud je povoleno. Toto by mělo být použito jako příkaz k obnovení účtu pouze v případě, že je uživatel uzamčen mimo jeho účet.',
            'Pokud toto nechcete udělat, stiskněte CTRL + C pro ukončení tohoto procesu.',
        ],
        '2fa_disabled' => 'Dvoufázové ověření bylo vypnuto pro :email.',
    ],
    'schedule' => [
        'output_line' => 'Odesílání první úlohy v `:schedule` (:id).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Odstraňování záložního souboru služby :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Žádost o obnovení „:name“ (#:id) v uzlu „:node“ selhala s chybou: :message',
        'reinstall' => [
            'failed' => 'Žádost o přeinstalaci „:name“ (#:id) v uzlu „:node“ selhala s chybou: :message',
            'confirm' => 'Chystáte se přeinstalovat skupinu serverů. Chcete pokračovat?',
        ],
        'power' => [
            'confirm' => 'Chystáte se provést :action proti :count serverům. Přejete si pokračovat?',
            'action_failed' => 'Požadavek na výkonovou akci „:name“ (#:id) v uzlu „:node“ selhal s chybou: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP hostitel (např. smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP port',
            'ask_smtp_username' => 'SMTP Uživatelské jméno',
            'ask_smtp_password' => 'SMTP heslo',
            'ask_mailgun_domain' => 'Mailgun doména (doména)',
            'ask_mailgun_endpoint' => 'Mailgun Endpoint',
            'ask_mailgun_secret' => 'Mailgun tajný klíč',
            'ask_mandrill_secret' => 'Mandrill Tajný klíč',
            'ask_postmark_username' => 'Postmark API klíč',
            'ask_driver' => 'Který ovladač by měl být použit pro odesílání e-mailů?',
            'ask_mail_from' => 'E-mailové adresy by měly pocházet z',
            'ask_mail_name' => 'Název, ze kterého by se měly zobrazit e-maily',
            'ask_encryption' => 'Používat šifrovací metodu',
        ],
    ],
];
