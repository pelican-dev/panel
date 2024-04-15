<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Échec de la connexion',
        'success' => 'Connecté',
        'password-reset' => 'Réinitialisation du mot de passe',
        'reset-password' => 'Demande de réinitialisation de mot de passe',
        'checkpoint' => 'Authentification à deux facteurs demandée',
        'recovery-token' => 'Jeton de récupération à deux facteurs utilisé',
        'token' => 'Défi à deux facteurs résolu',
        'ip-blocked' => 'Demande bloquée provenant d\'une adresse IP non répertoriée pour :identifier',
        'sftp' => [
            'fail' => 'Échec de la connexion SFTP',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Changement d\'adresse électronique de :old à :new',
            'password-changed' => 'Mot de passe modifié',
        ],
        'api-key' => [
            'create' => 'Création d\'une nouvelle clé API :identifiant',
            'delete' => 'Clé API supprimée :identifiant',
        ],
        'ssh-key' => [
            'create' => 'Ajout de la clé SSH :fingerprint au compte',
            'delete' => 'Suppression de la clé SSH :fingerprint du compte',
        ],
        'two-factor' => [
            'create' => 'Activation de l\'authentification à deux facteurs',
            'delete' => 'Authentification à deux facteurs désactivée',
        ],
    ],
    'server' => [
        'reinstall' => 'Serveur réinstallé',
        'console' => [
            'command' => '":command" exécutée sur le serveur',
        ],
        'power' => [
            'start' => 'Le serveur a été démarré',
            'stop' => 'Le serveur a été arrêté',
            'restart' => 'Le serveur a été redémarré',
            'kill' => 'Processus du serveur tué',
        ],
        'backup' => [
            'download' => 'Sauvegarde :name téléchargée',
            'delete' => 'Sauvegarde :name supprimée',
            'restore' => 'Sauvegarde :name restaurée (fichiers supprimés: :truncate)',
            'restore-complete' => 'Restauration de la sauvegarde :name terminée',
            'restore-failed' => 'Échec de la restauration de la sauvegarde :name',
            'start' => 'Lancement d\'une nouvelle sauvegarde :name',
            'complete' => 'La sauvegarde :name a été marquée comme terminée',
            'fail' => 'La sauvegarde :name a échoué',
            'lock' => 'La sauvegarde :name est verrouillée',
            'unlock' => 'La sauvegarde :name a été déverrouillée',
        ],
        'database' => [
            'create' => 'Nouvelle base de données créée :name',
            'rotate-password' => 'Mot de passe renouvelé pour la base de données :name',
            'delete' => 'Base de données :name supprimée',
        ],
        'file' => [
            'compress_one' => ':directory:file compressé',
            'compress_other' => ':count fichiers compressés dans :directory',
            'read' => 'Consulté le contenu de :file',
            'copy' => 'Copie de :file créée',
            'create-directory' => 'Répertoire :directory:name créé',
            'decompress' => 'Décompressé :files dans :directory',
            'delete_one' => 'Supprimé :directory:files.0',
            'delete_other' => ':count fichiers supprimés dans :directory',
            'download' => ':file téléchargé',
            'pull' => 'Téléchargé un fichier distant depuis :url vers :directory',
            'rename_one' => ':directory:files.0.from renommé en :directory:files.0.to',
            'rename_other' => ':count fichiers renommés dans :directory',
            'write' => 'Nouveau contenu :file écrit',
            'upload' => 'Début du téléversement',
            'uploaded' => 'Ajouté (upload) :directory:file',
        ],
        'sftp' => [
            'denied' => 'Accès SFTP bloqué en raison d\'autorisations',
            'create_one' => ':files.0 créés',
            'create_other' => ':count nouveaux fichiers ont été créés',
            'write_one' => 'Modification du contenu de :files.0',
            'write_other' => ':count fichiers ont été modifiés',
            'delete_one' => 'Suppression de :files.0',
            'delete_other' => 'Suppression de :count fichiers',
            'create-directory_one' => 'Création du dossier :files.0',
            'create-directory_other' => ':count dossier ont été créé',
            'rename_one' => 'Le fichier a été renommé de :files.0.from à :files.0.to',
            'rename_other' => ':count fichiers ont été renommés ou déplacés',
        ],
        'allocation' => [
            'create' => ':allocation a été ajoutée au serveur',
            'notes' => 'Mise à jour des notes pour :allocation de ":old" à ":new"',
            'primary' => 'Changement de :allocation en tant qu\'allocation principale du serveur',
            'delete' => 'Suppression de l\'allocation :allocation',
        ],
        'schedule' => [
            'create' => 'Création de la planification :name',
            'update' => 'Modification de la planification :name',
            'execute' => 'Exécution manuelle de la planification :name',
            'delete' => 'Suppression de la planification :name',
        ],
        'task' => [
            'create' => 'Création d\'une nouvelle tâche ":action" pour la planification :name',
            'update' => 'Modification de la tâche ":action" pour la planification :name',
            'delete' => 'Suppression de la planification :name',
        ],
        'settings' => [
            'rename' => 'Le serveur a été renommé de :old à :new',
            'description' => 'La description du serveur a été changée de :old à :new',
        ],
        'startup' => [
            'edit' => 'La variable ":variable" a été changée de ":old" à ":new"',
            'image' => 'L\'image Docker a été changée de :old à :new',
        ],
        'subuser' => [
            'create' => 'Ajout de :email en tant que sous-utilisateur',
            'update' => 'Modification des permissions du sous-utilisateur :email',
            'delete' => 'Suppression du sous-utilisateur :email',
        ],
    ],
];
