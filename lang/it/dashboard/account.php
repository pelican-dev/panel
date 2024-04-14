<?php

return [
    'email' => [
        'title' => 'Aggiorna la tua email',
        'updated' => 'Il tuo indirizzo email e stato aggiornato.',
    ],
    'password' => [
        'title' => 'Cambia la tua password',
        'requirements' => 'La tua nuova password deve essere lunga almeno 8 caratteri.',
        'updated' => 'La password è stata aggiornata.',
    ],
    'two_factor' => [
        'button' => 'Configura l\'autenticazione a due fattori',
        'disabled' => 'L\'autenticazione a due fattori è stata disabilitata sul tuo account. Non ti sarà più richiesto di fornire un token durante l\'accesso.',
        'enabled' => 'L\'autenticazione a due fattori è stata abilitata sul tuo account! D\'ora in poi, quando accedi, ti sarà richiesto di fornire il codice generato dal tuo dispositivo.',
        'invalid' => 'Il token fornito non è valido.',
        'setup' => [
            'title' => 'Imposta l\'autenticazione a due fattori',
            'help' => 'Non puoi scansionare il codice? Inserisci il codice qui sotto nella tua applicazione:',
            'field' => 'Inserisci il token',
        ],
        'disable' => [
            'title' => 'Disabilita l\'autenticazione a due fattori',
            'field' => 'Inserisci il token',
        ],
    ],
];
