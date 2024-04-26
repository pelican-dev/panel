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
        'name' => 'A simple, human-readable name to use as an identifier for this Egg.',
        'description' => 'A description of this Egg that will be displayed throughout the Panel as needed.',
        'uuid' => 'This is the globally unique identifier for this Egg which Wings uses as an identifier.',
        'author' => 'The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.',
        'force_outgoing_ip' => "Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.\nRequired for certain games to work properly when the Node has multiple public IP addresses.\nEnabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node.",
        'startup' => 'The default startup command that should be used for new servers using this Egg.',
        'docker_images' => 'The docker images available to servers using this egg.',
    ],
];
