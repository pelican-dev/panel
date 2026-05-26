<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => 'Indiquez l\'adresse e-mail utilisée pour l\'exportation des eggs par ce Panel. Elle doit être valide.',
            'url' => 'L\'URL de l\'application doit commencer par https:// ou http:// selon que vous utilisez SSL ou non. Si vous n\'incluez pas le schéma, les liens dans vos e-mails et autres contenus pointeront vers un emplacement incorrect.',
            'timezone' => "La timezone doit correspondre à une des timezones supporté par PHP. Si vous n'êtes pas sûr, merci de regarder les références https://php.net/manual/en/timezones.php.",
        ],
        'redis' => [
            'note' => 'Vous avez sélectionné le driver Redis pour une ou plusieurs options, merci de fournir des informations de connexion valide ci-dessous. Dans la plupart des cas, vous pouvez utiliser ceux fournit par défaut sauf si vous avec modifié votre installation.',
            'comment' => 'Par défaut une instance de serveur Redis a comme nom d\'utilisateur celui par défaut et n\'a pas de mot de passe tant qu\'elle fonctionne en local et est inaccessible depuis l\'extérieur. Si c\'est le cas, appuyez simplement sur Entrée sans saisir de valeur.',
            'confirm' => 'Il semblerait que :field est déjà défini pour Redis, êtes-vous sûr de vouloir le changer ?',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => 'Il est fortement recommandé de ne pas utiliser "localhost" pour votre base de donnée car nous remarquons fréquemment des erreurs de connexion au socket. Si vous souhaitez utiliser une connexion locale, vous devriez utiliser "127.0.0.1".',
        'DB_USERNAME_note' => "Utiliser le compte root pour les connexions MySQL n'est pas seulement fortement déconseillé, il est aussi interdit par cette application. Vous allez avoir besoin de créer un utilisateur MySQL pour ce logiciel.",
        'DB_PASSWORD_note' => 'Il semble que vous ayez déjà un mot de passe de connexion MySQL, voulez-vous le changer ?',
        'DB_error_2' => 'Vos identifiants de connexion n\'ont PAS été sauvegardé. Vous allez avoir besoin de fournir des informations de connexion valide avant de continuer.',
        'go_back' => 'Retourner en arrière et réessayer',
    ],
    'make_node' => [
        'name' => 'Entrez un identifiant court à utiliser pour distinguer cette node des autres',
        'description' => 'Entrer une description pour identifier cette node',
        'scheme' => 'Merci d\'entrer https pour une connexion SSL ou http pour une connexion non-SSL.',
        'fqdn' => 'Entrer un nom de domaine (ex : node.example.com) à utiliser pour se connecter au daemon. Une adresse IP peut seulement être utilisé si vous n\'utiliser par SSL pour cette node.',
        'public' => 'Est-ce que cette node doit être publique ? Remarque : en définissant une node comme privé, vous empêcherez le déploiement automatique sur cette node.',
        'behind_proxy' => 'Est-ce que votre FQDN est derrière un proxy ?',
        'maintenance_mode' => 'Le mode maintenance doit-il être activé ?',
        'memory' => 'Entrer la quantité maximale de mémoire',
        'memory_overallocate' => 'Entrer la quantité de mémoire à sur-allouer, -1 désactivera la vérification et 0 empêchera la création de nouveaux serveurs',
        'disk' => 'Entrer la quantité maximale de stockage',
        'disk_overallocate' => 'Entrer la quantité de stockage à sur-allouer, -1 désactivera la vérification et 0 empêchera la création de nouveau serveur',
        'cpu' => 'Entrer la quantité maximale de CPU',
        'cpu_overallocate' => 'Entrer la quantité de CPU à sur-allouer, -1 désactivera la vérification et 0 empêchera la création de nouveau serveur',
        'upload_size' => 'Saisir la taille maximale des fichiers à télécharger',
        'daemonListen' => 'Entrer le port du daemon',
        'daemonConnect' => 'Entrez le port de connexion du service (peut être le même que le port d\'écoute)',
        'daemonSFTP' => 'Entrer le port SFTP du daemon',
        'daemonSFTPAlias' => 'Saisir l\'alias SFTP du daemon (peut être vide)',
        'daemonBase' => 'Entrer le dossier de base',
        'success' => 'Une nouvelle node portant le nom :name a été créé avec succès avec l\'identifiant :id',
    ],
    'node_config' => [
        'error_not_exist' => 'La node choisi n\'existe pas.',
        'error_invalid_format' => 'Le format spécifié n\'est pas valide. Les formats valides sont yaml et json.',
    ],
    'key_generate' => [
        'error_already_exist' => 'Il semble que vous ayez déjà configuré une clé de cryptage d\'application. La poursuite de ce processus risque d\'écraser cette clé et de corrompre les données cryptées existantes. NE CONTINUEZ PAS SI VOUS NE SAVEZ PAS CE QUE VOUS FAITES.',
        'understand' => 'Je comprends les conséquences de l\'exécution de cette commande et accepte toute la responsabilité en cas de perte de données chiffrées.',
        'continue' => 'Êtes-vous sûr de vouloir continuer ? La modification de la clé de chiffrement de l\'application CAUSERA UNE PERTE DE DONNÉES.',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => 'Il n\'y a pas de tâches planifiées pour les serveurs qui doivent être exécutées.',
            'error_message' => 'Une erreur a été rencontrée lors du traitement de la planification : ',
        ],
    ],
];
