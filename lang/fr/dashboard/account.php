<?php

return [
    'title' => 'Aperçu du compte',
    'email' => [
        'title' => 'Mettre à jour l\'adresse e-mail',
        'button' => 'Mettre à jour l\'e-mail',
        'updated' => 'Votre adresse e-mail principale a été mise à jour.',
    ],
    'password' => [
        'title' => 'Mettre à jour le mot de passe',
        'button' => 'Mettre à jour le mot de passe',
        'requirements' => 'Votre nouveau mot de passe doit comporter au moins 8 caractères et être unique à ce site web.',
        'validation' => [
            'account_password' => 'Vous devez fournir le mot de passe de votre compte.',
            'current_password' => 'Vous devez fournir votre mot de passe actuel.',
            'password_confirmation' => 'La confirmation du mot de passe ne correspond pas au mot de passe que vous avez saisi.',
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
            'subtitle' => 'Aidez à protéger votre compte contre les accès non autorisés. Un code de vérification vous sera demandé chaque fois que vous vous connecterez.',
            'help' => 'Scannez le code QR ci-dessus avec l\'application d\'authentification à deux facteurs de votre choix. Ensuite, entrez le code à 6 chiffres généré dans le champ ci-dessous.',
        ],

        'required' => [
            'title' => 'Authentification à deux facteurs requise.',
            'description' => 'Votre compte doit avoir l\'authentification à deux facteurs activée pour continuer.',
        ],
    ],
];
