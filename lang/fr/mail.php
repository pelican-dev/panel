<?php

return [
    'greeting' => 'Bonjour :name !',

    'account_created' => [
        'body' => 'Vous recevez cet e-mail parce qu\'un compte a été créé pour vous sur :app.',
        'username' => 'Nom d\'utilisateur : :username',
        'email' => 'Email : :email',
        'action' => 'Configurer votre compte',
    ],

    'added_to_server' => [
        'body' => 'Vous avez été ajouté en tant que sous-utilisateur pour le serveur suivant, vous permettant de contrôler certaines choses sur le serveur.',
        'server_name' => 'Nom du serveur : :name',
        'action' => 'Visiter le serveur',
    ],

    'removed_from_server' => [
        'body' => 'Vous avez été supprimé en tant que sous-utilisateur pour le serveur suivant.',
        'server_name' => 'Nom du serveur : :name',
        'action' => 'Visiter le panel',
    ],

    'server_installed' => [
        'body' => 'Votre serveur a terminé son installation et est maintenant prêt à être utilisé.',
        'server_name' => 'Nom du serveur : :name',
        'action' => 'Connectez-vous et commencez à utiliser',
    ],

    'backup_completed' => [
        'body_success' => 'La sauvegarde a été créée avec succès.',
        'body_failed' => 'La création de la sauvegarde a échoué.',
        'backup_name' => 'Nom de la sauvegarde: :name',
        'server_name' => 'Nom du serveur: :name',
        'action' => 'Afficher les sauvegardes',
    ],

    'mail_tested' => [
        'subject' => 'Message de test du panel',
        'body' => 'Ceci est un test du système de messagerie de Panel. Vous pouvez continuer !',
    ],
];
