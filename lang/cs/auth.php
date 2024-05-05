<?php

return [
    'return_to_login' => 'Návrat na přihlášení',
    'failed' => 'Nebyl nalezen žádný účet odpovídající těmto přihlašovacím údajům.',

    'login' => [
        'title' => 'Přihlaste se pro pokračování',
        'button' => 'Přihlásit se',
        'required' => [
            'username_or_email' => 'Musíte zadat uživatelské jméno nebo email.',
            'password' => 'Zadejte prosím heslo k účtu.',
        ],
    ],

    'forgot_password' => [
        'title' => 'Žádost o obnovení hesla',
        'label' => 'Zapomněli jste heslo?',
        'label_help' => 'Zadejte e-mailovou adresu vašeho účtu pro příjem pokynů k obnovení hesla.',
        'button' => 'Odeslat email',
        'required' => [
            'email' => 'Pro pokračování musí být uvedena platná emailová adresa.',
        ],
    ],

    'reset_password' => [
        'title' => 'Obnovení hesla',
        'button' => 'Obnovit heslo',
        'new_password' => 'Nové heslo',
        'confirm_new_password' => 'Potvrďte nové heslo',
        'requirement' => [
            'password' => 'Heslo musí být dlouhé alespoň 8 znaků',
        ],
        'required' => [
            'password' => 'Vyžadováno nové heslo.',
            'password_confirmation' => 'Nová hesla se neshodují.',
        ],
        'validation' => [
            'password' => 'Nové heslo by mělo být dlouhé alespoň 8 znaků.',
            'password_confirmation' => 'Nová hesla se neshodují.',
        ],
    ],

    'checkpoint' => [
        'title' => 'Kontrolní bod zařízení',
        'recovery_code' => 'Kódy pro obnovení',
        'recovery_code_description' => 'Zadejte jeden z obnovovacích kódů vytvořených při nastavování dvoufázového ověření na tomto účtu pro pokračování.',
        'authentication_code' => 'Ověřovací kód',
        'authentication_code_description' => 'Zadejte dvoufázový token vygenerovaný vaším zařízením.',
        'button' => 'Pokračovat',
        'lost_device' => 'Ztratil jsem své zařízení',
        'have_device' => 'Mám své zařízení',
    ],

    'two_factor' => [
        'label' => 'Dvoufázový token',
        'label_help' => 'Tento účet vyžaduje druhou vrstvu ověřování, abyste mohli pokračovat. Pro dokončení přihlášení zadejte kód generovaný zařízením.',
        'checkpoint_failed' => 'Dvoufaktorový ověřovací token je neplatný.',
    ],

    'throttle' => 'Příliš mnoho pokusů o přihlášení. Zkuste to prosím znovu za :seconds sekund.',
    'password_requirements' => 'Heslo musí mít délku nejméně 8 znaků a mělo by být pro tento web jedinečné.',
    '2fa_must_be_enabled' => 'Správce požaduje, aby bylo dvoufázové ověření povoleno pro váš účet, aby jste mohl použit panel.',
];
