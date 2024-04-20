<?php

return [
    'email' => [
        'title' => 'E-mailadres wijzigen',
        'updated' => 'Het e-mailadres is succesvol gewijzigd.',
    ],
    'password' => [
        'title' => 'Wachtwoord wijzigen',
        'requirements' => 'Je nieuwe wachtwoord moet minstens 8 tekens bevatten.',
        'updated' => 'Het wachtwoord is succesvol gewijzigd.',
    ],
    'two_factor' => [
        'button' => 'Tweestapsverificatie configureren',
        'disabled' => 'Tweestapsverificatie is uitgeschakeld voor je account. Je wordt niet meer gevraagd om een code op te geven bij het inloggen.',
        'enabled' => 'Tweestapsverificatie is ingeschakeld op je account! Vanaf nu moet je bij het inloggen de code opgeven die door je apparaat wordt gegenereerd.',
        'invalid' => 'De opgegeven code is ongeldig.',
        'setup' => [
            'title' => 'Tweestapsverificatie instellen',
            'help' => 'Kan de code niet worden gescand? Voer de onderstaande code in je applicatie:',
            'field' => 'Code invoeren',
        ],
        'disable' => [
            'title' => 'Tweestapsverificatie uitschakelen',
            'field' => 'Code invoeren',
        ],
    ],
];
