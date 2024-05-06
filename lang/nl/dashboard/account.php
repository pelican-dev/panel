<?php

return [
    'title' => 'Accountoverzicht',
    'email' => [
        'title' => 'E-mailadres wijzigen',
        'button' => 'E-mail bijwerken',
        'updated' => 'Het e-mailadres is succesvol gewijzigd.',
    ],
    'password' => [
        'title' => 'Wachtwoord wijzigen',
        'button' => 'Wachtwoord Bijwerken',
        'requirements' => 'Je nieuwe wachtwoord moet minstens 8 tekens bevatten.',
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
            'title' => 'Tweestapsverificatie instellen',
            'subtitle' => "Help je account te beschermen tegen ongeautoriseerde toegang. Je wordt om een verificatiecode gevraagd telkens wanneer je inlogt.",
            'help' => 'Kan de code niet worden gescand? Voer de onderstaande code in je applicatie:',
        ],

        'required' => [
            'title' => 'Tweestapsverificatie Vereist',
            'description' => 'Je account moet tweestapsverificatie ingeschakeld hebben om door te gaan.',
        ],
    ],
];
