<?php

return [
    'daemon_connection_failed' => 'Une erreur est survenue lors de la tentative de communication avec le démon, entraînant un code de réponse HTTP/:code. Cette exception a été enregistrée.',
    'node' => [
        'servers_attached' => 'Un nœud ne doit avoir aucun serveur lié à lui pour être supprimé.',
        'daemon_off_config_updated' => 'The daemon configuration <strong>has been updated</strong>, however there was an error encountered while attempting to automatically update the configuration file on the Daemon. You will need to manually update the configuration file (config.yml) for the daemon to apply these changes.',
    ],
    'allocations' => [
        'server_using' => 'A server is currently assigned to this allocation. An allocation can only be deleted if no server is currently assigned.',
        'too_many_ports' => 'L\'ajout de plus de 1000 ports dans une seule plage à la fois n\'est pas supporté.',
        'invalid_mapping' => 'The mapping provided for :port was invalid and could not be processed.',
        'cidr_out_of_range' => 'La notation CIDR permet uniquement les masques entre /25 et /32.',
        'port_out_of_range' => 'Ports in an allocation must be greater than 1024 and less than or equal to 65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'An Egg with active servers attached to it cannot be deleted from the Panel.',
        'invalid_copy_id' => 'The Egg selected for copying a script from either does not exist, or is copying a script itself.',
        'has_children' => 'This Egg is a parent to one or more other Eggs. Please delete those Eggs before deleting this Egg.',
    ],
    'variables' => [
        'env_not_unique' => 'The environment variable :name must be unique to this Egg.',
        'reserved_name' => 'La variable d\'environnement :name est protégée et ne peut pas être assignée à une variable.',
        'bad_validation_rule' => 'The validation rule ":rule" is not a valid rule for this application.',
    ],
    'importer' => [
        'json_error' => 'Une erreur s\'est produite lors de l\'analyse du fichier JSON: :error.',
        'file_error' => 'Le fichier JSON fourni n\'est pas valide.',
        'invalid_json_provided' => 'Le fichier JSON fourni n\'est pas dans un format qui peut être reconnu.',
    ],
    'subusers' => [
        'editing_self' => 'Vous n\'êtes pas autorisé à modifier votre propre compte de sous-utilisateur',
        'user_is_owner' => 'Vous ne pouvez pas ajouter le propriétaire du serveur en tant que sous-utilisateur pour ce serveur.',
        'subuser_exists' => 'Un utilisateur avec cette adresse e-mail est déjà assigné en tant que sous-utilisateur pour ce serveur.',
    ],
    'databases' => [
        'delete_has_databases' => 'Cannot delete a database host server that has active databases linked to it.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'L\'intervalle maximum pour une tâche chaînée est de 15 minutes.',
    ],
    'locations' => [
        'has_nodes' => 'Cannot delete a location that has active nodes attached to it.',
    ],
    'users' => [
        'node_revocation_failed' => 'Failed to revoke keys on <a href=":link">Node #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Aucun nœud satisfaisant les exigences spécifiées pour le déploiement automatique n\'a pu être trouvé.',
        'no_viable_allocations' => 'Aucun nœud satisfaisant les exigences spécifiées pour le déploiement automatique n\'a pu être trouvé.',
    ],
    'api' => [
        'resource_not_found' => 'La ressource demandée n\'existe pas sur ce serveur.',
    ],
];
