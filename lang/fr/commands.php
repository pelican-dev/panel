<?php

return [

    /* Not yet added to the panel
    "debug" => [
        "enable" => "Debug mode has been enabled",
        "disable" => "Debug mode has been disabled",
        "disable_option" => "Disable Debug mode",
        "enable_option" => "Disable Debug mode",
        "cancel_option" => "Cancel the Command",
    ],
    */
    'appsettings' => [
        'comment' => [
            'author' => 'Indiquez l\'adresse e-mail à partir de laquelle les œufs exportés par ce panneau devraient provenir. L\'adresse e-mail doit être valide.',
            'url' => 'L\'URL de l\'application DOIT commencer par https:// ou http:// selon que vous utilisiez SSL ou non. Si vous n\'incluez pas ce schéma, vos e-mails et autres contenus seront liés à un mauvais emplacement.',
            'timezone' => "Le fuseau horaire doit correspondre à l'un des fuseaux horaires pris en charge par PHP. Si vous n\\'êtes pas sûr, veuillez consulter https://php.net/manual/en/timezones.php.",
            //"lang" => "Choose a language you want to use on your panel.",
            'settings_ui' => 'Activer l\'éditeur de paramètres de l\'interface utilisateur ?',
        ],
        /*
        "lang" => [
        "question" => "What language do you want to use?",
        ],
        */
        'redis' => [
            'note' => 'Vous avez sélectionné le pilote Redis pour une ou plusieurs options, veuillez fournir des informations de connexion valides ci-dessous. Dans la plupart des cas, vous pouvez utiliser les valeurs par défaut fournies sauf si vous avez modifié votre configuration.',
            'comment' => 'Par défaut, une instance de serveur Redis n\'a pas de mot de passe car elle fonctionne localement et n\'est pas accessible depuis l\'extérieur. Si c\'est le cas, appuyez simplement sur Entrée sans entrer une valeur.',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => 'Il est fortement recommandé de ne pas utiliser "localhost" comme hôte de la base de données car nous avons rencontré de fréquents problèmes de connexion aux sockets. Si vous voulez utiliser une connexion locale, vous devriez utiliser "127.0.0.1".',
        'DB_USERNAME_note' => "L\\'utilisation du compte racine pour les connexions MySQL est non seulement fortement déconseillée, mais elle n'est pas non plus autorisée par cette application. Vous devrez avoir créé un utilisateur MySQL pour ce logiciel.",
        'DB_PASSWORD_note' => 'Il semble que vous ayez déjà un mot de passe de connexion MySQL, voulez-vous le changer ?',
        'DB_error_2' => 'Vos identifiants de connexion n\'ont PAS été enregistrés. Vous devrez fournir des informations de connexion valides avant de continuer.',
    ],
    'make_node' => [
        'name' => 'Entrez un identifiant court utilisé pour distinguer ce nœud des autres',
        'description' => 'Entrez une description pour identifier le nœud',
        'scheme' => 'S\'il vous plaît entrez HTTPS pour une connexion SSL ou HTTP pour une connexion non-SSL',
        'fqdn' => 'Entrez un nom de domaine (par exemple node.example.com) à utiliser pour se connecter au démon. Une adresse IP ne peut être utilisée que si vous n\'utilisez pas SSL pour ce nœud',
        'public' => 'Ce nœud doit-il être public ? Remarque : en définissant un nœud comme privé, vous refuserez la possibilité de déployer automatiquement sur ce nœud.',
        'behind_proxy' => 'Est-ce que votre FQDN est derrière un proxy ?',
        'maintenance_mode' => 'Le mode maintenance doit-il être activé ?',
        'memory' => 'Entrez la quantité maximale de mémoire',
        'memory_overallocate' => 'Entrez la quantité de mémoire à sur-allouer, -1 désactivera la vérification et 0 empêchera la création de nouveaux serveurs',
        'disk' => 'Entrez la quantité maximale de stockage',
        'disk_overallocate' => 'Entrez la quantité de mémoire à sur-allouer, -1 désactivera la vérification et 0 empêchera la création d\'un nouveau serveur',
        'upload_size' => "Entrez la taille maximale de téléchargement du fichier",
        'daemonListen' => 'Entrez le port d\'écoute du démon',
        'daemonSFTP' => 'Entrez le port d\'écoute SFTP du démon',
        'daemonBase' => 'Entrez le dossier de base',
        'succes1' => 'Un nouveau nœud avec le nom a été créé avec succès : ',
        'succes2' => 'et son identifiant est : ',
    ],
    'node_config' => [
        'error_not_exist' => 'Le nœud choisi n\'existe pas.',
        'error_invalid_format' => 'Format spécifié non valide. Les options valides sont YAML et JSON.',
    ],
    'key_generate' => [
        'error_already_exist' => 'Il semble que vous ayez déjà configuré une clé de chiffrement de l\'application. Continuer ce processus va écraser cette clé et provoquer une corruption des données chiffrées existantes. NE CONTINUEZ QUE SI VOUS SAVEZ CE QUE VOUS FAITES.',
        'understand' => 'Je comprends les conséquences de l\'exécution de cette commande et accepte toute responsabilité pour la perte de données chiffrées.',
        'continue' => 'Êtes-vous sûr de vouloir continuer ? La modification de la clé de chiffrement de l\'application VA CAUSER DES PERTES DE DONNÉES.',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => 'Il n\'y a pas de tâches planifiées pour les serveurs qui doivent être exécutées.',
            'error_message' => 'Une erreur est survenue lors du traitement du programme : ',
        ],
    ],
    'upgrade' => [
        'integrity' => 'Cette commande ne vérifie pas l\'intégrité des ressources téléchargées. Veuillez vous assurer que vous faites confiance à la source du téléchargement avant de continuer. Si vous ne souhaitez pas télécharger une archive, veuillez l\'indiquer en utilisant l\'option --skip-download, ou en répondant "non" à la question ci-dessous.',
        'source_url' => 'Source du téléchargement (définie avec --url=) :',
        'php_version' => 'Impossible d\'exécuter le processus d\'auto mise-à-jour. La version minimale requise de PHP est la 7.4.0, vous avez',
        'skipDownload' => 'Voulez-vous télécharger et décompresser les fichiers d\'archive pour la dernière version ?',
        'webserver_user' => 'L\'utilisateur de votre serveur web a été détecté comme <fg=blue>[{:user}]:</> est-ce correct ?',
        'name_webserver' => 'Veuillez entrer le nom de l\'utilisateur qui exécute le processus de votre serveur web. Cela varie d\'un système à l\'autre, mais est généralement "www-data", "nginx" ou "apache".',
        'group_webserver' => 'Le groupe de votre serveur web a été détecté comme <fg=blue>[{:group}]:</> est-ce correct ?',
        'group_webserver_question' => 'Veuillez entrer le nom du groupe qui exécute le processus de votre serveur web. Normalement, c\'est le même que votre utilisateur.',
        'are_your_sure' => 'Êtes-vous sûr de vouloir exécuter le processus de mise à jour pour votre Panel ?',
        'terminated' => 'Processus de mise-à-jour arrêté par l\'utilisateur.',
        'success' => 'Le Panel a été mis à jour avec succès. Veuillez vous assurer que vous mettez également à jour toutes les instances de démons',

    ],
];
