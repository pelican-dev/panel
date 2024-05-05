<?php

return [
    'notices' => [
        'imported' => 'Cet Œuf et ses variables ont été importés avec succès.',
        'updated_via_import' => 'Cet Œuf a été mis à jour en utilisant le fichier fourni.',
        'deleted' => 'L\'Œuf demandé a été supprimé du panneau.',
        'updated' => 'La configuration de l\'œuf a été mise à jour avec succès.',
        'script_updated' => 'Le script d\'installation d\'oeuf a été mis à jour et s\'exécutera chaque fois que les serveurs sont installés.',
        'egg_created' => 'Un nouvel œuf a été créé avec succès. Vous devrez redémarrer tous les démons en cours d\'exécution pour appliquer ce nouvel œuf.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'La variable ":variable" a été supprimée et ne sera plus disponible pour les serveurs une fois reconstruits.',
            'variable_updated' => 'La variable ":variable" a été mise à jour. Vous devrez reconstruire tous les serveurs utilisant cette variable pour appliquer les modifications.',
            'variable_created' => 'La nouvelle variable a été créée et affectée à cet œuf.',
        ],
    ],
    'descriptions' => [
        'name' => 'Un nom simple et facile à lire à utiliser comme identifiant pour cet Œuf.',
        'description' => 'Une description de cet Œuf qui sera affichée dans le panel selon les besoins.',
        'uuid' => 'Il s\'agit de l\'identifiant unique global pour cet Œuf que Wings utilise comme identifiant.',
        'author' => 'L\'auteur de cette version de l\'Œuf. Le téléchargement d\'une nouvelle configuration d\'Œuf d\'un auteur différent le modifiera.',
        'force_outgoing_ip' => "Force tout le trafic réseau sortant à avoir son adresse IP source NATée avec l'IP de l'allocation principale du serveur.\nNécessaire pour que certains jeux fonctionnent correctement lorsque le nœud possède plusieurs adresses IP publiques.\nL'activation de cette option désactivera le réseau interne pour tous les serveurs utilisant cet Egg, les empêchant ainsi d'accéder à d'autres serveurs sur le même nœud via le réseau interne.",
        'startup' => 'La commande de démarrage par défaut qui devrait être utilisée pour les nouveaux serveurs utilisant cet Œuf.',
        'docker_images' => 'Les images Docker disponibles pour les serveurs utilisant cet Œuf.',
    ],
];
