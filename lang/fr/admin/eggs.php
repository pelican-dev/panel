<?php

return [
    'notices' => [
        'imported' => 'Cet Egg et ses variables ont été importés avec succès.',
        'updated_via_import' => 'Cet Egg a été mis à jour en utilisant le fichier fourni.',
        'deleted' => 'L\'Egg demandé a été supprimé du panneau.',
        'updated' => 'La configuration de l\'egg a été mise à jour avec succès.',
        'script_updated' => 'Le script d\'installation de l\'egg a été mis à jour et s\'exécutera chaque fois que les serveurs sont installés.',
        'egg_created' => 'Un nouvel egg a été pondu avec succès. Vous devrez redémarrer tous les daemons en cours d\'exécution pour appliquer ce nouvel egg.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'La variable ":variable" a été supprimée et ne sera plus disponible pour les serveurs une fois reconstruits.',
            'variable_updated' => 'La variable ":variable" a été mise à jour. Vous devrez reconstruire tous les serveurs utilisant cette variable pour appliquer les modifications.',
            'variable_created' => 'La nouvelle variable a été créée et affectée à cet egg.',
        ],
    ],
];
