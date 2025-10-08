<?php

return [
    'user' => [
        'search_users' => 'Voer een gebruikersnaam, gebruikers-ID of e-mailadres in',
        'select_search_user' => 'ID van te verwijderende gebruiker (Vul \'0\' in om opnieuw te zoeken)',
        'deleted' => 'Gebruiker succesvol verwijderd uit het paneel.',
        'confirm_delete' => 'Weet u zeker dat u deze gebruiker wilt verwijderen uit het paneel?',
        'no_users_found' => 'Er zijn geen gebruikers gevonden voor de opgegeven zoekterm.',
        'multiple_found' => 'Er zijn meerdere accounts voor de gebruiker gevonden, het is niet mogelijk om een gebruiker te verwijderen vanwege de --no-interactie vlag.',
        'ask_admin' => 'Is deze gebruiker een beheerder?',
        'ask_email' => 'E-mailadres',
        'ask_username' => 'Gebruikersnaam',
        'ask_password' => 'Wachtwoord',
        'ask_password_tip' => 'Als je een account wilt aanmaken met een willekeurig wachtwoord dat naar de gebruiker wordt gestuurd, voer dit commando opnieuw uit (CTRL+C) en geef de `--no-password` parameter op.',
        'ask_password_help' => 'Wachtwoorden moeten minstens 8 tekens lang zijn en minstens één hoofdletter en één cijfer bevatten.',
        '2fa_help_text' => 'Dit commando schakelt tweestapsverificatie uit voor het account van een gebruiker. Dit commando dient alleen te worden gebruikt als herstel optie wanneer een gebruiker is buitengesloten. Als dit niet is wat je wil doen sluit je het proces af door op CTRL+C te drukken.',
        '2fa_disabled' => 'Tweestapsverificatie is uitgeschakeld voor :email.',
    ],
    'schedule' => [
        'output_line' => 'Verzenden van de eerste taak voor `:schedule` (:id).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Verwijderen service back-up bestand :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Herbouw verzoek voor ":name" (#:id) op node ":node" is mislukt met fout: :message',
        'reinstall' => [
            'failed' => 'Opnieuw installeren voor ":name" (#:id) op node ":node" is mislukt met fout: :message',
            'confirm' => 'U staat op het punt een groep servers opnieuw te installeren. Wilt u doorgaan?',
        ],
        'power' => [
            'confirm' => 'U staat op het punt een :action uit te voeren tegen :count servers. Wilt u doorgaan?',
            'action_failed' => 'Power actie verzoek voor ":name" (#:id) op node ":node" is mislukt met fout: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP Host (bijv. smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP Poort',
            'ask_smtp_username' => 'SMTP Gebruikersnaam',
            'ask_smtp_password' => 'SMTP Wachtwoord',
            'ask_mailgun_domain' => 'Mailgun Domein',
            'ask_mailgun_endpoint' => 'Mailgun Adres',
            'ask_mailgun_secret' => 'Mailgun Wachtwoord',
            'ask_mandrill_secret' => 'Mandrill Wachtwoord',
            'ask_postmark_username' => 'Postmark API Sleutel',
            'ask_driver' => 'Welke driver moet worden gebruikt voor het verzenden van e-mails?',
            'ask_mail_from' => 'E-mailadres waar e-mails vandaan moeten komen',
            'ask_mail_name' => 'Naam waar e-mails van moeten verschijnen',
            'ask_encryption' => 'Te gebruiken encryptiemethode',
        ],
    ],
];
