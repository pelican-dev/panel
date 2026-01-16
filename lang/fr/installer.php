<?php

return [
    'title' => 'Installateur du panneau de gestion',
    'requirements' => [
        'title' => 'Configuration serveur requise',
        'sections' => [
            'version' => [
                'title' => 'Version de PHP',
                'or_newer' => ':version ou plus récent',
                'content' => 'Votre version de PHP est :version.',
            ],
            'extensions' => [
                'title' => 'Extensions PHP',
                'good' => 'Toutes les extensions PHP nécessaires sont installées.',
                'bad' => 'Les extensions PHP suivantes sont manquantes : :extensions',
            ],
            'permissions' => [
                'title' => 'Droits d\'accès au dossier',
                'good' => 'Tous les dossiers ont les droits corrects.',
                'bad' => 'Les dossiers suivants ont des droits incorrects : :folders',
            ],
        ],
        'exception' => 'Certaines exigences sont manquantes',
    ],
    'environment' => [
        'title' => 'Environnement',
        'fields' => [
            'app_name' => 'Nom de l\'application',
            'app_name_help' => 'Ce sera le nom de votre panneau de gestion.',
            'app_url' => 'URL de l\'application',
            'app_url_help' => 'Ce sera l\'URL à partir de laquelle vous accéderez à votre panneau de gestion.',
            'account' => [
                'section' => 'Administrateur',
                'email' => 'E-mail',
                'username' => 'Nom d\'utilisateur',
                'password' => 'Mot de passe',
            ],
        ],
    ],
    'database' => [
        'title' => 'Base de données',
        'driver' => 'Pilote de la base de données',
        'driver_help' => 'Le pilote utilisé pour la base de données du panneau de gestion. Nous vous recommandons "SQLite".',
        'fields' => [
            'host' => 'Hôte de base de données',
            'host_help' => 'L\'hôte de votre base de données. Assurez-vous qu\'il est accessible.',
            'port' => 'Port de la base de données',
            'port_help' => 'Le port de votre base de données.',
            'path' => 'Chemin de la base de données',
            'path_help' => 'Le chemin de votre fichier .sqlite relatif au dossier de la base de données.',
            'name' => 'Nom de la base de donnée',
            'name_help' => 'Le nom de la base de données du panneau de gestion.',
            'username' => 'Nom d\'utilisateur de la base de données',
            'username_help' => 'Le nom de l\'utilisateur de votre base de données.',
            'password' => 'Mot de passe de la base de données',
            'password_help' => 'Le mot de passe de l\'utilisateur de votre base de données. Il peut être vide.',
        ],
        'exceptions' => [
            'connection' => 'Échec de la connexion à la base de données.',
            'migration' => 'Échec de la migration',
        ],
    ],
    'egg' => [
        'title' => 'Œufs',
        'no_eggs' => 'Aucun œuf disponible',
        'background_install_started' => 'Installation de l’œuf démarrée',
        'background_install_description' => 'L’installation de :count œufs a été mise en file d\'attente et se poursuivra en arrière-plan.',
        'exceptions' => [
            'failed_to_update' => 'Échec de la mise à jour de l’index des œufs',
            'no_eggs' => 'Aucun œuf n’est disponible pour l’installation pour le moment.',
            'installation_failed' => 'Échec de l’installation des œufs sélectionnés. Veuillez les importer après l’installation via la liste des œufs.',
        ],
    ],
    'session' => [
        'title' => 'Session',
        'driver' => 'Pilote de session',
        'driver_help' => 'Le pilote utilisé pour stocker les sessions. Nous recommandons "Système de fichiers" ou "Base de données".',
    ],
    'cache' => [
        'title' => 'Cache',
        'driver' => 'Pilote du cache',
        'driver_help' => 'Le pilote utilisé pour la mise en cache. Nous recommandons "Système de fichiers".',
        'fields' => [
            'host' => 'Hôte Redis',
            'host_help' => 'L\'hôte de votre serveur redis. Assurez-vous qu\'il est accessible.',
            'port' => 'Port Redis',
            'port_help' => 'Le port de votre serveur redis.',
            'username' => 'Nom d\'utilisateur Redis',
            'username_help' => 'Le nom de votre utilisateur redis. Il peut être vide',
            'password' => 'Mot de passe Redis',
            'password_help' => 'Le mot de passe de votre utilisateur redis. Peut-être vide.',
        ],
        'exception' => 'Échec de la connexion à Redis',
    ],
    'queue' => [
        'title' => 'File d\'attente',
        'driver' => 'Pilote de file d\'attente',
        'driver_help' => 'Le pilote utilisé pour la gestion des files d\'attente. Nous vous recommandons "Base de données".',
        'fields' => [
            'done' => 'J\'ai fait les deux étapes ci-dessous.',
            'done_validation' => 'Vous devez faire les deux étapes avant de continuer !',
            'crontab' => 'Exécutez la commande suivante pour configurer votre crontab. Notez que <code>www-data</code> est votre utilisateur du serveur web. Sur certains systèmes, ce nom d\'utilisateur peut être différent !',
            'service' => 'Pour configurer le queue worker, vous devez simplement exécuter la commande suivante.',
        ],
    ],
    'exceptions' => [
        'write_env' => 'Impossible d\'écrire dans le fichier .env',
        'migration' => 'Impossible d\'exécuter les migrations',
        'create_user' => 'Impossible de créer l\'utilisateur admin',
    ],
    'next_step' => 'Etape suivante',
    'finish' => 'Terminer',
];
