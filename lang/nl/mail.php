<?php

return [
    'greeting' => 'Beste :name!',

    'account_created' => [
        'body' => 'Je ontvangt deze e-mail omdat er een account voor je is aangemaakt op :app.',
        'username' => 'Gebruikersnaam: :username',
        'email' => 'E-mail: :email',
        'action' => 'Stel je account in',
    ],

    'added_to_server' => [
        'body' => 'U bent toegevoegd als een sub gebruiker voor de volgende server, waardoor u bepaalde controle over de server kan krijgen.',
        'server_name' => 'Servernaam: :name',
        'action' => 'Bezoek server',
    ],

    'removed_from_server' => [
        'body' => 'Je bent verwijderd als sub gebruiker voor de volgende server.',
        'server_name' => 'Servernaam: :name',
        'action' => 'Bezoek paneel',
    ],

    'server_installed' => [
        'body' => 'Uw server is klaar met installeren en is nu klaar voor gebruik.',
        'server_name' => 'Servernaam: :name',
        'action' => 'Login en begin met gebruik',
    ],

    'backup_completed' => [
        'body_success' => 'De back-up is succesvol gemaakt.',
        'body_failed' => 'Het aanmaken van de backup is mislukt.',
        'backup_name' => 'Backup naam :name',
        'server_name' => 'Server naam :name',
        'action' => 'Bekijk backups',
    ],

    'mail_tested' => [
        'subject' => 'Paneel test bericht',
        'body' => 'Dit is een test van het paneel mailsysteem. Je bent klaar om te gaan.',
    ],
];
