<?php

return [
    'user' => [
        'search_users' => 'Introduceți un nume de utilizator, ID utilizator sau adresă de e-mail',
        'select_search_user' => 'ID-ul utilizatorului pentru șters (Introduceți \'0\' pentru a căuta din nou)',
        'deleted' => 'Utilizatorul a fost șters din Panou cu succes.',
        'confirm_delete' => 'Sunteți sigur ca doriți sa ștergeți utilizatorul din Panou?',
        'no_users_found' => 'Nu au fost găsiți utilizatori pentru termenul de căutare furnizat.',
        'multiple_found' => 'Au fost găsite mai multe conturi pentru utilizatorul furnizat, incapabil să ștergi un utilizator din cauza flag-ului --no-interaction ence.',
        'ask_admin' => 'Este acest utilizator un administrator?',
        'ask_email' => 'Adresa de e-mail',
        'ask_username' => 'Nume de utilizator',
        'ask_password' => 'Parolă',
        'ask_password_tip' => 'Dacă doriți să creați un cont cu o parolă aleatorie trimisă prin e-mail utilizatorului, re-rulați această comandă (CTRL+C) și pasați steagul `--no-password`.',
        'ask_password_help' => 'Parolele trebuie să aibă cel puțin 8 caractere și să conțină cel puțin o literă majusculă și un număr.',
        '2fa_help_text' => 'Această comandă va dezactiva autentificarea în doi pași pentru contul unui utilizator dacă este activată. Ar trebui folosită doar ca o comandă de recuperare a contului dacă utilizatorul este blocat. Dacă nu asta voiai să faci, apasă CTRL+C pentru a ieși din proces.',
        '2fa_disabled' => 'Autentificarea cu doi factori a fost dezactivată pentru :email.',
    ],
    'schedule' => [
        'output_line' => 'Expedierea jobului pentru prima sarcină în `:schedule` (:id).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Ștergere copie de siguranță a serviciului :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Solicitarea de reconstruire pentru ":name" (#:id) pe nodul ":node" a eșuat cu eroare: :message',
        'reinstall' => [
            'failed' => 'Solicitarea de reinstalare pentru ":name" (#:id) pe nodul ":node" a eșuat cu eroare: :message',
            'confirm' => 'Sunteți pe cale să reinstalați împotriva unui grup de servere. Doriți să continuați?',
        ],
        'power' => [
            'confirm' => 'Sunteți pe cale să efectuați o :action împotriva serverelor :count . Doriți să continuați?',
            'action_failed' => 'Cererea de pornire pentru ":name" (#:id) pe nodul ":node" a eșuat cu eroare: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'Gazda SMTP (de ex. smtp.gmail.com)',
            'ask_smtp_port' => 'Port SMTP',
            'ask_smtp_username' => 'Utilizator SMTP',
            'ask_smtp_password' => 'Parolă SMTP',
            'ask_mailgun_domain' => 'Domeniul Mailgun',
            'ask_mailgun_endpoint' => 'Mailgun Endpoint',
            'ask_mailgun_secret' => 'Mailgun Secret',
            'ask_mandrill_secret' => 'Secret Mandrill',
            'ask_postmark_username' => 'Cheie API Postmark',
            'ask_driver' => 'Ce driver ar trebui folosit pentru trimiterea de e-mailuri?',
            'ask_mail_from' => 'E-mailurile cu adresa de e-mail trebuie să provină de la',
            'ask_mail_name' => 'Numele de la care ar trebui să apară e-mailurile',
            'ask_encryption' => 'Metoda de criptare folosită',
        ],
    ],
];
