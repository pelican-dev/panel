<?php

return [
    'email' => [
        'title' => 'Aktualizujte svoj e-mail',
        'updated' => 'Vaša e-mailová adresa bola aktualizovaná.',
    ],
    'password' => [
        'title' => 'Zmeň si heslo',
        'requirements' => 'Vaše nové heslo by malo mať aspoň 8 znakov.',
        'updated' => 'Vaše heslo bolo aktualizované.',
    ],
    'two_factor' => [
        'button' => 'Nakonfigurujte 2-Faktorové overenie',
        'disabled' => 'Dvojfaktorová autentifikácia bola vo vašom účte zakázaná. Pri prihlásení sa vám už nebude zobrazovať výzva na zadanie tokenu.',
        'enabled' => 'Na vašom účte bola aktivovaná dvojfaktorová autentifikácia! Odteraz budete pri prihlasovaní musieť zadať kód vygenerovaný vaším zariadením.',
        'invalid' => 'Poskytnutý token bol neplatný.',
        'setup' => [
            'title' => 'Nastavte dvojfaktorové overenie',
            'help' => 'Nemôžete naskenovať kód? Do svojej aplikácie zadajte nižšie uvedený kód:',
            'field' => 'Zadajte token',
        ],
        'disable' => [
            'title' => 'Zakázať dvojfaktorové overenie',
            'field' => 'Zadajte token',
        ],
    ],
];
