<?php

return [
    'title' => 'Aperçu du compte',
    'email' => [
        'title' => 'Update Email Address',
        'button' => 'Update Email',
        'updated' => 'Votre adresse e-mail principale a été mise à jour.',
    ],
    'password' => [
        'title' => 'Mettre à jour le mot de passe',
        'button' => 'Mettre à jour le mot de passe',
        'requirements' => 'Votre nouveau mot de passe doit comporter au moins 8 caractères et être unique à ce site web.',
        'validation' => [
            'account_password' => 'You must provide your account password.',
            'current_password' => 'Vous devez fournir votre mot de passe actuel.',
            'password_confirmation' => 'Password confirmation does not match the password you entered.',
        ],
        'updated' => 'Votre mot de passe a été mis à jour.',
    ],
    'two_factor' => [
        'title' => 'Vérification en deux étapes',
        'button' => 'Configurer l\'authentificateur à deux facteurs',
        'disabled' => 'L\'authentification à deux facteurs a été désactivée sur votre compte. Vous ne serez plus invité à fournir le code généré par votre appareil lors de votre connexion.',
        'enabled' => 'L\'authentification à deux facteurs a été activée sur votre compte ! Désormais, lorsque vous vous connectez, vous devrez fournir le code généré par votre appareil.',
        'invalid' => 'Le jeton fourni est invalide.',
        'enable' => [
            'help' => 'Vous n\'avez pas encore activé la vérification en deux étapes sur votre compte. Cliquez sur le bouton ci-dessous pour commencer à la configurer.',
            'button' => 'Vérification en deux étapes',
        ],
        'disable' => [
            'help' => 'La vérification en deux étapes est actuellement activée sur votre compte.',
            'title' => 'Désactiver l\'authentification à deux facteurs',
            'field' => 'Saisir un jeton',
            'button' => 'Désactiver la vérification en deux étapes',
        ],
        'setup' => [
            'title' => 'Activer la vérification en deux étapes',
            'subtitle' => "Help protect your account from unauthorized access. You'll be prompted for a verification code each time you sign in.",
            'help' => 'Scan the QR code above using the two-step authentication app of your choice. Then, enter the 6-digit code generated into the field below.',
        ],

        'required' => [
            'title' => '2-Factor Required',
            'description' => 'Votre compte doit avoir l\'authentification à deux facteurs activée pour continuer.',
        ],
    ],
];
