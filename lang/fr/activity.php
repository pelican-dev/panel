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
        'checkpoint' => 'Authentification à deux facteurs demandée',
        'recovery-token' => 'Jeton de récupération d\'authentification à deux facteurs utilisé',
        'token' => 'Défi d\'authentification à deux facteurs résolu',
        'ip-blocked' => 'Requête bloquée provenant d\'une adresse IP non répertoriée pour <b>:identifier</b>',
        'sftp' => [
            'fail' => 'Échec de la connexion SFTP',
        ],
    ],
    'user' => [
        'account' => [
            'username-changed' => 'Nom d\'utilisateur modifié de <b>:old</b> à <b>:new</b>',
            'email-changed' => 'E-mail modifié de <b>:old</b> à <b>:new</b>',
            'password-changed' => 'Mot de passe modifié',
        ],
        'api-key' => [
            'create' => 'Création d\'une nouvelle clé d\'API <b>:identifiant</b>',
            'delete' => 'Suppression d\'une clé d\'API <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => 'Ajout de la clé SSH <b>:fingerprint</b> au compte',
            'delete' => 'Suppression de la clé SSH <b>:fingerprint</b> du compte',
        ],
        'two-factor' => [
            'create' => 'Activation de l\'authentification à deux facteurs',
            'delete' => 'Authentification à deux facteurs désactivée',
        ],
    ],
    'server' => [
        'console' => [
            'command' => '"<b>:command</b>" exécutée sur le serveur',
        ],
        'power' => [
            'start' => 'Le serveur a été démarré',
            'stop' => 'Le serveur a été arrêté',
            'restart' => 'Le serveur a été redémarré',
            'kill' => 'Le serveur a été arrêté de force',
        ],
        'backup' => [
            'download' => 'Sauvegarde <b>:name</b> téléchargée',
            'delete' => 'Sauvegarde <b>:name</b> supprimée',
            'restore' => 'Sauvegarde <b>:name</b> restaurée (fichiers supprimés : <b>:truncate</b>)',
            'restore-complete' => 'Restauration de la sauvegarde <b>:name</b> terminée',
            'restore-failed' => 'Échec de la restauration de la sauvegarde <b>:name</b>',
            'start' => 'Lancement d\'une nouvelle sauvegarde <b>:name</b>',
            'complete' => 'La sauvegarde <b>:name</b> a été marquée comme terminée',
            'fail' => 'La sauvegarde <b>:name</b> a échoué',
            'lock' => 'La sauvegarde <b>:name</b> a été verrouillée',
            'unlock' => 'La sauvegarde <b>:name</b> a été déverrouillée',
            'rename' => 'Sauvegarde renommée de "<b>:old_name</b>" en "<b>:new_name</b>',
        ],
        'database' => [
            'create' => 'Nouvelle base de données créée <b>:name</b>',
            'rotate-password' => 'Mot de passe renouvelé pour la base de données <b>:name</b>',
            'delete' => 'Base de données <b>:name</b> supprimée',
        ],
        'file' => [
            'compress' => 'Compression de <b>:directory:files</b>|<b>:count</b> fichiers compressés dans <b>:directory</b>',
            'read' => 'Consulté le contenu de <b>:file</b>',
            'copy' => 'Copie de <b>:file</b> créée',
            'create-directory' => 'Répertoire <b>:directory:name</b> créé',
            'decompress' => 'Le fichier <b>:file</b> a été décompressée dans <b>:directory</b>',
            'delete' => 'Suppression de <b>:directory:files</b>|<b>:count</b> fichiers supprimer dans <b>:directory</b>',
            'download' => 'Téléchargement en cours de <b>:file</b>',
            'pull' => 'Téléchargé un fichier distant depuis <b>:url</b> vers <b>:directory</b>',
            'rename' => 'Déplacé/renommé <b>:from</b> to <b>:to</b>|Déplacé/ Renommé <b>:count</b> files in <b>:directory</b>',
            'write' => 'Nouveau contenu écrit dans le fichier <b>:file</b>',
            'upload' => 'Début de l\'envoi de fichier',
            'uploaded' => '<b>:directory:file</b> envoyée',
        ],
        'sftp' => [
            'denied' => 'Accès SFTP bloqué à cause des permissions',
            'create' => 'Création du fichier <b>:files</b>|<b>:count</b> nouveaux fichiers ont été crées',
            'write' => 'Modification du fichier <b>:files</b>|Le contenu de <b>:count</b> fichiers a été modifié',
            'delete' => 'Suppression du fichier <b>:files</b>|Suppression de <b>:count</b> fichiers',
            'create-directory' => 'Création du dossier <b>:files</b>|Création de <b>:count</b> dossiers',
            'rename' => 'Le fichier a été renommé de <b>:from</b> à <b>:to</b>|<b>:count</b> fichiers ont été renommés ou déplacer',
        ],
        'allocation' => [
            'create' => '<b>:allocation</b> a été ajoutée au serveur',
            'notes' => 'Mise à jour des notes pour <b>:allocation</b> de "<b>:old</b>" à "<b>:new</b>"',
            'primary' => 'Change de <b>:allocation</b> en tant qu\'allocation principale du serveur',
            'delete' => 'Suppression de l\'allocation <b>:allocation</b>',
        ],
        'schedule' => [
            'create' => 'Création de la planification <b>:name</b>',
            'update' => 'Mise à jour de la planification <b>:name</b>',
            'execute' => 'Exécution manuelle de la planification <b>:name</b>',
            'delete' => 'Suppression de la planification <b>:name</b>',
        ],
        'task' => [
            'create' => 'Création de l\'action "<b>:action</b>" pour la planification <b>:name</b>',
            'update' => 'Mise à jour de l\'action "<b>:action</b>" pour la planification <b>:name</b>',
            'delete' => 'Mise à jour de l\'action "<b>:action</b>" pour la planification <b>:name</b>',
        ],
        'settings' => [
            'rename' => 'Le serveur a été renommé de "<b>:old</b>" à "<b>:new</b>"',
            'description' => 'La description du serveur a changé de "<b>:old</b>" à "<b>:new</b>"',
            'reinstall' => 'Serveur réinstallé',
        ],
        'startup' => [
            'edit' => 'Changement de la variable <b>:variable</b> de "<b>:old</b>" à "<b>:new</b>"',
            'image' => 'Mise à jour de l\'image Docker pour le serveur de <b>:old</b> à <b>:new</b>',
            'command' => 'Commande de démarrage mise à jour pour le serveur de <b>:old</b> à <b>:new</b>',
        ],
        'subuser' => [
            'create' => 'Ajout de <b>:email</b> en tant que sous-utilisateur',
            'update' => 'Modification des permissions du sous-utilisateur <b>:email</b>',
            'delete' => 'Suppression du sous-utilisateur <b>:email</b>',
        ],
        'crashed' => 'Le serveur a planté',
    ],
];
