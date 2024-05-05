<?php

return [
    'return_to_login' => 'Revenir à la page de connexion',
    'failed' => 'Aucun compte correspondant à ces identifiants n\'a été trouvé.',

    'login' => [
        'title' => 'Connectez-vous pour continuer',
        'button' => 'Connexion',
        'required' => [
            'username_or_email' => 'Un nom d\'utilisateur ou une adresse e-mail doit être fourni.',
            'password' => 'Veuillez entrer le mot de passe de votre compte.',
        ],
    ],

    'forgot_password' => [
        'title' => 'Demande de réinitialisation du mot de passe',
        'label' => 'Mot de passe oublié ?',
        'label_help' => 'Entrez votre adresse e-mail pour recevoir des instructions sur la réinitialisation de votre mot de passe.',
        'button' => 'Envoyer l\'e-mail',
        'required' => [
            'email' => 'Une adresse e-mail valide doit être fournie pour continuer.',
        ],
    ],

    'reset_password' => [
        'title' => 'Réinitialiser le mot de passe',
        'button' => 'Réinitialiser le mot de passe',
        'new_password' => 'Nouveau mot de passe',
        'confirm_new_password' => 'Confirmer le nouveau mot de passe',
        'requirement' => [
            'password' => 'Les mots de passe doivent comporter au moins 8 caractères.',
        ],
        'required' => [
            'password' => 'Un nouveau mot de passe est requis.',
            'password_confirmation' => 'Votre nouveau mot de passe ne correspond pas.',
        ],
        'validation' => [
            'password' => 'Votre nouveau mot de passe doit comporter au moins 8 caractères.',
            'password_confirmation' => 'Votre nouveau mot de passe ne correspond pas.',
        ],
    ],

    'checkpoint' => [
        'title' => 'Point de contrôle de l\'appareil',
        'recovery_code' => 'Code de récupération',
        'recovery_code_description' => 'Saisissez l\'un des codes de récupération générés lorsque vous avez configuré l\'authentification à deux facteurs sur ce compte pour continuer.',
        'authentication_code' => 'Code d\'authentification',
        'authentication_code_description' => 'Saisissez le jeton d\'authentification à deux facteurs généré par votre appareil.',
        'button' => 'Continuer',
        'lost_device' => "J'ai perdu mon appareil",
        'have_device' => 'J\'ai mon appareil',
    ],

    'two_factor' => [
        'label' => 'Jeton 2-Factor',
        'label_help' => 'Ce compte nécessite une deuxième authentification pour continuer. Veuillez entrer le code généré par votre appareil pour terminer cette connexion.',
        'checkpoint_failed' => 'Le jeton d\'authentification à deux facteurs (2-factor) est invalide.',
    ],

    'throttle' => 'Trop de tentatives de connexion. Merci de réessayer dans :seconds secondes.',
    'password_requirements' => 'Le mot de passe doit contenir au moins 8 caractères et doit être unique à ce site.',
    '2fa_must_be_enabled' => 'L\'administrateur a demandé que soit activé une double authentification pour votre compte, afin d\'utiliser le Panel',
];
