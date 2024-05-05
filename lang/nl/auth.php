<?php

return [
    'return_to_login' => 'Keer terug naar inloggen',
    'failed' => 'Er werd geen account gevonden dat overeenkomt met deze inloggegevens.',

    'login' => [
        'title' => 'Log in om verder te gaan.',
        'button' => 'Inloggen',
        'required' => [
            'username_or_email' => 'Een gebruikersnaam of wachtwoord moet worden gegeven.',
            'password' => 'Voer het wachtwoord van uw account in.',
        ],
    ],

    'forgot_password' => [
        'title' => 'Wachtwoord reset aanvragen',
        'label' => 'Wachtwoord Vergeten?',
        'label_help' => 'Voer het e-mailadres van uw account in om instructies te ontvangen over het opnieuw instellen van uw wachtwoord.',
        'button' => 'Verstuur e-mail',
        'required' => [
            'email' => 'Een geldig e-mailadres moet worden opgegeven om door te gaan.',
        ],
    ],

    'reset_password' => [
        'title' => 'Wachtwoord opnieuw instellen',
        'button' => 'Wachtwoord opnieuw instellen',
        'new_password' => 'Nieuw Wachtwoord',
        'confirm_new_password' => 'Bevestig nieuw wachtwoord',
        'requirement' => [
            'password' => 'Wachtwoorden moeten tenminste 8 tekens lang zijn.',
        ],
        'required' => [
            'password' => 'Een nieuw wachtwoord is vereist.',
            'password_confirmation' => 'Uw nieuwe wachtwoord komt niet overeen.',
        ],
        'validation' => [
            'password' => 'Je nieuwe wachtwoord moet minstens 8 tekens lang zijn.',
            'password_confirmation' => 'Uw nieuwe wachtwoord komt niet overeen.',
        ],
    ],

    'checkpoint' => [
        'title' => 'Apparaat Controlepunt',
        'recovery_code' => 'Herstelcode',
        'recovery_code_description' => 'Voer een van de herstelcodes in die gegenereerd zijn bij het instellen van 2-Factor authenticatie voor dit account om door te gaan.',
        'authentication_code' => 'Authenticatie Code',
        'authentication_code_description' => 'Voer het tweestapstoken in dat door uw apparaat gegenereerd is.',
        'button' => 'Volgende',
        'lost_device' => 'Ik heb Mijn Apparaat kwijt',
        'have_device' => 'Ik heb mijn apparaat',
    ],

    'two_factor' => [
        'label' => 'Tweestapsverificatie code',
        'label_help' => 'Dit account heeft tweestapsverificatie aanstaan. Voer de door uw apparaat gegenereerde code in om deze login te voltooien.',
        'checkpoint_failed' => 'De tweestapsverificatie code was ongeldig.',
    ],

    'throttle' => 'Te veel inlogpogingen. Probeer het over :seconds seconden opnieuw.',
    'password_requirements' => 'Het wachtwoord moet minimaal 8 tekens lang zijn en moet uniek zijn voor deze site.',
    '2fa_must_be_enabled' => 'De beheerder heeft vereist dat tweestapsverificatie voor je account is ingeschakeld om het paneel te kunnen gebruiken.',
];
