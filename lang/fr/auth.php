<?php

return [
    'sign_in' => 'Se connecter',
    'go_to_login' => 'Aller à la connexion',
    'failed' => 'Aucun compte correspondant à ces identifiants n\'a été trouvé.',

    'forgot_password' => [
        'label' => 'Mot de passe oublié ?',
        'label_help' => 'Entrez votre adresse e-mail pour recevoir des instructions sur la réinitialisation de votre mot de passe.',
        'button' => 'Récupérer un compte',
    ],

    'reset_password' => [
        'button' => 'Réinitialiser et se connecter',
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
