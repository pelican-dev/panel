<?php

return [
    'title' => 'Account Overview',
    'email' => [
        'title' => 'Update Email Address',
        'button' => 'Update Email',
        'updated' => 'Your primary email has been updated.',
    ],
    'password' => [
        'title' => 'Update Password',
        'button' => 'Update Password',
        'requirements' => 'Your new password should be at least 8 characters in length and unique to this website.',
        'validation' => [
            'account_password' => 'You must provide your account password.',
            'current_password' => 'You must provide your current password.',
            'password_confirmation' => 'Password confirmation does not match the password you entered.',
        ],
        'updated' => 'Votre mot de passe a été mis à jour.',
    ],
    'two_factor' => [
        'title' => 'Two-Step Verification',
        'button' => 'Configurer l\'authentificateur à deux facteurs',
        'disabled' => 'L\'authentification à deux facteurs a été désactivée sur votre compte. Vous ne serez plus invité à fournir le code généré par votre appareil lors de votre connexion.',
        'enabled' => 'L\'authentification à deux facteurs a été activée sur votre compte ! Désormais, lorsque vous vous connectez, vous devrez fournir le code généré par votre appareil.',
        'invalid' => 'Le jeton fourni est invalide.',
        'enable' => [
            'help' => 'You do not currently have two-step verification enabled on your account. Click the button below to begin configuring it.',
            'button' => 'Enable Two-Step',
        ],
        'disable' => [
            'help' => 'Two-step verification is currently enabled on your account.',
            'title' => 'Désactiver l\'authentification à deux facteurs',
            'field' => 'Saisir un jeton',
            'button' => 'Disable Two-Step',
        ],
        'setup' => [
            'title' => 'Enable Two-Step Verification',
            'subtitle' => "Help protect your account from unauthorized access. You'll be prompted for a verification code each time you sign in.",
            'help' => 'Scan the QR code above using the two-step authentication app of your choice. Then, enter the 6-digit code generated into the field below.',
        ],

        'required' => [
            'title' => '2-Factor Required',
            'description' => 'Your account must have two-factor authentication enabled in order to continue.',
        ],
    ],
];
