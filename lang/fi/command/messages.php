<?php

return [
    'user' => [
        'search_users' => 'Anna käyttäjänimi, käyttäjätunnus tai sähköpostiosoite',
        'select_search_user' => 'Poistavan käyttäjän tunnus (Paina Enter \'0\' uudelleenhakua varten)',
        'deleted' => 'Käyttäjä poistettiin onnistuneesti paneelista.',
        'confirm_delete' => 'Oletko varma, että haluat poistaa tämän käyttäjän paneelista?',
        'no_users_found' => 'Yhtään käyttäjää ei löytynyt hakusanalla.',
        'multiple_found' => 'Useita tilejä löytyi annetulle käyttäjälle. Käyttäjää ei voitu poistaa --no-interaction -lipun takia.',
        'ask_admin' => 'Onko tämä käyttäjä järjestelmänvalvoja?',
        'ask_email' => 'Sähköpostiosoite',
        'ask_username' => 'Käyttäjänimi',
        'ask_password' => 'Salasana',
        'ask_password_tip' => 'Mikäli haluat luoda tilin satunnaisella salasanalla, joka lähetetään sähköpostitse käyttäjälle, suorita tämä komento uudelleen (CTRL+C) ja lisää --no-password tunniste.',
        'ask_password_help' => 'Salasanan on oltava vähintään 8 merkkiä pitkä ja siinä on oltava vähintään yksi iso kirjain ja numero.',
        '2fa_help_text' => [
            'Tämä komento poistaa käytöstä kaksivaiheisen todennuksen käyttäjän tililtä, jos se on käytössä. Tätä tulee käyttää tilin palautuskomennona vain, jos käyttäjä on lukittu pois tililtään.',
            'Jos tämä ei ole sitä, mitä halusit tehdä, paina CTRL+C poistuaksesi tästä prosessista.',
        ],
        '2fa_disabled' => '2-tekijän todennus on poistettu käytöstä sähköpostilta :email.',
    ],
    'schedule' => [
        'output_line' => 'Lähetetään työtä ensimmäiseen tehtävään `:schedule` (:hash).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Poistetaan palvelun varmuuskopiotiedostoa :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Uudelleenrakennus pyyntö ":name" (#:id) solmussa ":node" epäonnistui virheellä: :message',
        'reinstall' => [
            'failed' => 'Pyyntö ":name" (#:id) uudelleenasennuksesta palvelimessa ":node" epäonnistui virheellä: :message',
            'confirm' => 'Olet tekemässä uudelleenasennusta useille palvelimille. Haluatko jatkaa?',
        ],
        'power' => [
            'confirm' => 'Haluatko jatkaa :action :count palvelimelle?',
            'action_failed' => 'Virtatoiminnon pyyntö ":name" (#:id) solmussa ":node" epäonnistui virheellä: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP Isäntä (esim. smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP portti',
            'ask_smtp_username' => 'SMTP Käyttäjätunnus',
            'ask_smtp_password' => 'SMTP Salasana',
            'ask_mailgun_domain' => 'Mailgun Verkkotunnus',
            'ask_mailgun_endpoint' => 'Mailgun päätepiste',
            'ask_mailgun_secret' => 'Mailgun Salaisuus',
            'ask_mandrill_secret' => 'Mandrill Salaisuus',
            'ask_postmark_username' => 'Postmark API-avain',
            'ask_driver' => 'Mitä palvelua pitäisi käyttää sähköpostien lähetykseen?',
            'ask_mail_from' => 'Sähköpostiosoitteen sähköpostit tulee lähettää osoitteesta',
            'ask_mail_name' => 'Nimi, josta sähköpostit tulisi näyttää lähtevän',
            'ask_encryption' => 'Käytettävä salausmenetelmä',
        ],
    ],
];
