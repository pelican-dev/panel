<?php

return [
    'sign_in' => 'Login',
    'go_to_login' => 'Vai all\'accesso',
    'failed' => 'Non è stato trovato alcun account corrispondente a queste credenziali.',

    'forgot_password' => [
        'label' => 'Password Dimenticata?',
        'label_help' => 'Inserisci l\'indirizzo email del tuo account per ricevere le istruzioni per reimpostare la password.',
        'button' => 'Recupera Account',
    ],

    'reset_password' => [
        'button' => 'Reimposta e Accedi',
    ],

    'two_factor' => [
        'label' => 'Token a due fattori',
        'label_help' => 'Questo account richiede un secondo livello di autenticazione per continuare. Inserisci il codice generato dal tuo dispositivo per completare il login.',
        'checkpoint_failed' => 'Il token di autenticazione a due fattori non è valido.',
    ],

    'throttle' => 'Troppi tentativi di accesso. Riprova tra :seconds secondi.',
    'password_requirements' => 'La password deve essere di almeno 8 caratteri e deve essere unica per questo sito.',
    '2fa_must_be_enabled' => 'L\'amministratore ha richiesto che l\'autenticazione a due fattori sia abilitata per il tuo account per poter utilizzare il pannello.',
];
