<?php

return [
    'user' => [
        'search_users' => 'Zadajte Používateľské meno, ID Používateľa, alebo Emailovú Adresu',
        'select_search_user' => 'ID používateľa, ktorého chcete odstrániť (Zadajte "0" pre opätovné vyhľadávanie)',
        'deleted' => 'Používateľ bol úspešne odstránený z Panela.',
        'confirm_delete' => 'Naozaj chcete odstrániť tohto používateľa z panela?',
        'no_users_found' => 'Pre zadaný hľadaný výraz sa nenašli žiadni používatelia.',
        'multiple_found' => 'Pre zadaného používateľa sa našlo viacero účtov. Používateľa nebolo možné odstrániť kvôli flagu --no-interaction.',
        'ask_admin' => 'Je tento používateľ správcom?',
        'ask_email' => 'Emailová Adresa',
        'ask_username' => 'Používateľské meno',
        'ask_name_first' => 'Krstné meno',
        'ask_name_last' => 'Priezvisko',
        'ask_password' => 'Heslo',
        'ask_password_tip' => 'Ak by ste chceli vytvoriť účet s náhodným heslom zaslaným používateľovi e-mailom, spustite tento príkaz znova (CTRL+C) a zadajte flag `--no-password`.',
        'ask_password_help' => 'Heslá musia mať dĺžku aspoň 8 znakov a musia obsahovať aspoň jedno veľké písmeno a číslo.',
        '2fa_help_text' => [
            'Tento príkaz zakáže 2-faktorové overenie pre používateľský účet, ak je povolené. Toto by sa malo používať iba ako príkaz na obnovenie účtu, ak je používateľ zablokovaný vo svojom účte.',
            'Ak to nie je to, čo ste chceli urobiť, stlačte CTRL+C na ukončenie tohto procesu.',
        ],
        '2fa_disabled' => '2-Faktorové overenie bolo pre :email zakázané.',
    ],
    'schedule' => [
        'output_line' => 'Odosiela sa práca pre prvú úlohu v `:schedule` (:hash).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Odstraňuje sa záložný súbor služby :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Žiadosť o opätovné vytvorenie ":name" (#:id) v node ":node" zlyhala s chybou: :message',
        'reinstall' => [
            'failed' => 'Žiadosť o opätovnú inštaláciu ":name" (#:id) v node ":node" zlyhala s chybou: :message',
            'confirm' => 'Chystáte sa preinštalovať proti skupine serverov. Chcete pokračovať?',
        ],
        'power' => [
            'confirm' => 'Chystáte sa vykonať :akciu proti :count serverom. Chcete pokračovať?',
            'action_failed' => 'Žiadosť o akciu napájania pre ":name" (#:id) v node ":node" zlyhala s chybou: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP Host (napr. smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP Port',
            'ask_smtp_username' => 'SMTP Používateľské meno',
            'ask_smtp_password' => 'SMTP Heslo',
            'ask_mailgun_domain' => 'Mailgun Doména',
            'ask_mailgun_endpoint' => 'Mailgun Endpoint',
            'ask_mailgun_secret' => 'Mailgun Secret',
            'ask_mandrill_secret' => 'Mandrill Secret',
            'ask_postmark_username' => 'Postmark API Klúč',
            'ask_driver' => 'Ktorý ovládač by sa mal použiť na odosielanie e-mailov?',
            'ask_mail_from' => 'E-mailové adresy by mali pochádzať z',
            'ask_mail_name' => 'Meno, z ktorého sa majú e-maily zobrazovať',
            'ask_encryption' => 'Spôsob šifrovania, ktorý sa má použiť',
        ],
    ],
];
