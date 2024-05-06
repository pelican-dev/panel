<?php

return [
    'title' => 'Account Overview',
    'email' => [
        'title' => 'Aktualizujte svoj e-mail',
        'button' => 'Update Email',
        'updated' => 'Vaša e-mailová adresa bola aktualizovaná.',
    ],
    'password' => [
        'title' => 'Zmeň si heslo',
        'button' => 'Update Password',
        'requirements' => 'Vaše nové heslo by malo mať aspoň 8 znakov.',
        'validation' => [
            'account_password' => 'You must provide your account password.',
            'current_password' => 'You must provide your current password.',
            'password_confirmation' => 'Password confirmation does not match the password you entered.',
        ],
        'updated' => 'Vaše heslo bolo aktualizované.',
    ],
    'two_factor' => [
        'title' => 'Two-Step Verification',
        'button' => 'Nakonfigurujte 2-Faktorové overenie',
        'disabled' => 'Dvojfaktorová autentifikácia bola vo vašom účte zakázaná. Pri prihlásení sa vám už nebude zobrazovať výzva na zadanie tokenu.',
        'enabled' => 'Na vašom účte bola aktivovaná dvojfaktorová autentifikácia! Odteraz budete pri prihlasovaní musieť zadať kód vygenerovaný vaším zariadením.',
        'invalid' => 'Poskytnutý token bol neplatný.',
        'enable' => [
            'help' => 'You do not currently have two-step verification enabled on your account. Click the button below to begin configuring it.',
            'button' => 'Enable Two-Step',
        ],
        'disable' => [
            'help' => 'Two-step verification is currently enabled on your account.',
            'title' => 'Zakázať dvojfaktorové overenie',
            'field' => 'Zadajte token',
            'button' => 'Disable Two-Step',
        ],
        'setup' => [
            'title' => 'Nastavte dvojfaktorové overenie',
            'subtitle' => "Help protect your account from unauthorized access. You'll be prompted for a verification code each time you sign in.",
            'help' => 'Nemôžete naskenovať kód? Do svojej aplikácie zadajte nižšie uvedený kód:',
        ],

        'required' => [
            'title' => '2-Factor Required',
            'description' => 'Your account must have two-factor authentication enabled in order to continue.',
        ],
    ],
];
