<?php

return [
    'email' => [
        'title' => 'Mettre à jour votre adresse e-mail',
        'updated' => 'Votre adresse e-mail a été mise à jour.',
    ],
    'password' => [
        'title' => 'Modifier votre mot de passe',
        'requirements' => 'Votre nouveau mot de passe doit comporter au moins 8 caractères.',
        'updated' => 'Votre mot de passe a été mis à jour.',
    ],
    'two_factor' => [
        'button' => 'Configurer l\'authentificateur à deux facteurs',
        'disabled' => 'L\'authentification à deux facteurs a été désactivée sur votre compte. Vous ne serez plus invité à fournir le code généré par votre appareil lors de votre connexion.',
        'enabled' => 'L\'authentification à deux facteurs a été activée sur votre compte ! Désormais, lorsque vous vous connectez, vous devrez fournir le code généré par votre appareil.',
        'invalid' => 'Le jeton fourni est invalide.',
        'setup' => [
            'title' => 'Configurer l\'authentification à deux facteurs',
            'help' => 'Impossible de scanner le code QR ? Entrez le code ci-dessous dans votre application :',
            'field' => 'Saisir un jeton',
        ],
        'disable' => [
            'title' => 'Désactiver l\'authentification à deux facteurs',
            'field' => 'Saisir un jeton',
        ],
    ],
];
