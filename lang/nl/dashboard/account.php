<?php

return [
    'title' => 'Accountoverzicht',
    'email' => [
        'title' => 'E-mailadres Bijwerken',
        'button' => 'E-mail bijwerken',
        'updated' => 'Het primaire e-mailadres is bijgewerkt.',
    ],
    'password' => [
        'title' => 'Wachtwoord Bijwerken',
        'button' => 'Wachtwoord Bijwerken',
        'requirements' => 'Je nieuwe wachtwoord moet minstens 8 tekens lang zijn en uniek voor deze website.',
        'validation' => [
            'account_password' => 'Je moet jouw accountwachtwoord opgeven.',
            'current_password' => 'Vul je huidige wachtwoord in.',
            'password_confirmation' => 'De wachtwoordbevestiging komt niet overeen met het wachtwoord dat u hebt ingevoerd.',
        ],
        'updated' => 'Het wachtwoord is succesvol gewijzigd.',
    ],
    'two_factor' => [
        'title' => 'Tweestapsverificatie',
        'button' => 'Tweestapsverificatie configureren',
        'disabled' => 'Tweestapsverificatie is uitgeschakeld voor je account. Je wordt niet meer gevraagd om een code op te geven bij het inloggen.',
        'enabled' => 'Tweestapsverificatie is ingeschakeld op je account! Vanaf nu moet je bij het inloggen de code opgeven die door je apparaat wordt gegenereerd.',
        'invalid' => 'De opgegeven code is ongeldig.',
        'enable' => [
            'help' => 'Je hebt momenteel geen tweestapsverificatie ingeschakeld voor je account. Klik op de knop hieronder om te beginnen met het configureren.',
            'button' => 'Tweestapsverificatie inschakelen',
        ],
        'disable' => [
            'help' => 'Tweestapsverificatie is momenteel ingeschakeld voor je account.',
            'title' => 'Tweestapsverificatie uitschakelen',
            'field' => 'Code invoeren',
            'button' => 'Tweestapsverificatie uitschakelen',
        ],
        'setup' => [
            'title' => 'Tweestapsverificatie inschakelen',
            'subtitle' => 'Help je account te beschermen tegen ongeautoriseerde toegang. Je wordt om een verificatiecode gevraagd telkens wanneer je inlogt.',
            'help' => 'Scan de QR-code hierboven met behulp van de tweestapsverificatie-app naar jouw keuze. Voer daarna de 6-cijferige code in die wordt gegenereerd in het onderstaande veld.',
        ],

        'required' => [
            'title' => 'Tweestapsverificatie Vereist',
            'description' => 'Je account moet tweestapsverificatie ingeschakeld hebben om door te gaan.',
        ],
    ],
];
