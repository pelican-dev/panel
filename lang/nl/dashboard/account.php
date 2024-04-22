<?php

return [
    'title' => 'Account Overzicht',
    'email' => [
        'title' => 'E-mailadres wijzigen',
        'button' => 'Wijzig E-mailadres',
        'updated' => 'Jouw primaire e-mailadres is gewijzigd.',
    ],
    'password' => [
        'title' => 'Wachtwoord Wijzigen',
        'button' => 'Wijzig Wachtwoord',
        'requirements' => 'Jouw nieuwe wachtwoord zou te minste 8 karakters lang en uniek aan deze website moeten zijn.',
        'validation' => [
            'account_password' => 'Je moet jouw account wachtwoord opgeven.',
            'current_password' => 'Je moet jouw huidige wachtwoord opgeven.',
            'password_confirmation' => 'Wachtwoord bevesting komt niet overeen met het wachtwoord die je hebt ingevoerd.',
        ],
        'updated' => 'Jouw wachtwoord is gewijzigd.',
    ],
    'two_factor' => [
        'title' => 'Tweestapsverificatie',
        'button' => 'Configureer tweestapsverificatie',
        'disabled' => 'Tweestapsverificatie is uitgeschakeld voor je account. Je wordt niet meer gevraagd om een code op te geven bij het inloggen.',
        'enabled' => 'Tweestapsverificatie is ingeschakeld op je account! Vanaf nu moet je bij het inloggen de code opgeven die door je apparaat wordt gegenereerd.',
        'invalid' => 'De opgegeven code is ongeldig.',
        'enable' => [
            'help' => 'Je hebt op dit moment geen tweestapsverificatie ingeschakeld voor jouw account. Klik op de onderstaande knop om te beginnen met het configureren ervan.',
            'button' => 'Schakel Twee-Staps in',
        ],
        'disable' => [
            'help' => 'Tweestapsverificatie is op dit moment ingeschakeld voor jouw account.',
            'title' => 'Schakel twee-factor authenticatie uit',
            'field' => 'Voer token in',
            'button' => 'Schakel Twee-Staps uit',
        ],
        'setup' => [
            'title' => 'Schakel tweestapsverificatie in',
            'subtitle' => 'Help uw account te beschermen tegen ongeautoriseerde toegang. Elke keer dat u zich aanmeldt, wordt u om een ​​verificatiecode gevraagd.',
            'help' => 'Scan de bovenstaande QR-code met de tweestapsverificatie-app van uw keuze. Voer vervolgens de gegenereerde zescijferige code in het onderstaande veld in.',
        ],

        'required' => [
            'title' => 'Twee-Staps Verplicht',
            'description' => 'Voor uw account moet tweestapsverificatie zijn ingeschakeld om door te kunnen gaan.',
        ],
    ],
];
