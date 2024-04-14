<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Connexion échouée',
        'success' => 'Connecté',
        'password-reset' => 'Réinitialiser le mot de passe',
        'reset-password' => 'Demande de réinitialisation de mot de passe',
        'checkpoint' => 'Authentification à deux facteurs demandée',
        'recovery-token' => 'Jeton de récupération à deux facteurs utilisé',
        'token' => 'Défi à deux facteurs résolu',
        'ip-blocked' => 'Requête bloquée à partir d\'une adresse IP non listée pour :identifier',
        'sftp' => [
            'fail' => 'Connexion SFTP échouée',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Adresse e-mail changée de :old à :new',
            'password-changed' => 'Mot de passe modifié',
        ],
        'api-key' => [
            'create' => 'Nouvelle clé API créée :identifier',
            'delete' => 'Clé API supprimée :identifier',
        ],
        'ssh-key' => [
            'create' => 'Clé SSH :fingerprint ajoutée au compte',
            'delete' => 'Clé SSH :fingerprint retirée du compte',
        ],
        'two-factor' => [
            'create' => 'Authentification à deux facteurs activée',
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
            'uploaded' => 'Uploaded :directory:file',
        ],
        'sftp' => [
            'denied' => 'Blocked SFTP access due to permissions',
            'create_one' => ':files.0 créés',
            'create_other' => 'Created :count new files',
            'write_one' => 'Modified the contents of :files.0',
            'write_other' => 'Modified the contents of :count files',
            'delete_one' => 'Deleted :files.0',
            'delete_other' => 'Deleted :count files',
            'create-directory_one' => 'Created the :files.0 directory',
            'create-directory_other' => 'Created :count directories',
            'rename_one' => 'Renamed :files.0.from to :files.0.to',
            'rename_other' => 'Renamed or moved :count files',
        ],
        'allocation' => [
            'create' => 'Added :allocation to the server',
            'notes' => 'Updated the notes for :allocation from ":old" to ":new"',
            'primary' => 'Set :allocation as the primary server allocation',
            'delete' => 'Deleted the :allocation allocation',
        ],
        'schedule' => [
            'create' => 'Created the :name schedule',
            'update' => 'Updated the :name schedule',
            'execute' => 'Manually executed the :name schedule',
            'delete' => 'Deleted the :name schedule',
        ],
        'task' => [
            'create' => 'Created a new ":action" task for the :name schedule',
            'update' => 'Updated the ":action" task for the :name schedule',
            'delete' => 'Deleted a task for the :name schedule',
        ],
        'settings' => [
            'rename' => 'Renamed the server from :old to :new',
            'description' => 'Changed the server description from :old to :new',
        ],
        'startup' => [
            'edit' => 'Changed the :variable variable from ":old" to ":new"',
            'image' => 'Updated the Docker Image for the server from :old to :new',
        ],
        'subuser' => [
            'create' => 'Added :email as a subuser',
            'update' => 'Updated the subuser permissions for :email',
            'delete' => 'Removed :email as a subuser',
        ],
    ],
];
