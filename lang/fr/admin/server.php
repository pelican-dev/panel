<?php

return [
    'exceptions' => [
        'no_new_default_allocation' => 'Vous essayez de supprimer l\'allocation par défaut pour ce serveur, mais il n\'y a pas d\'allocation de secours à utiliser.',
        'marked_as_failed' => 'This server was marked as having failed a previous installation. Current status cannot be toggled in this state.',
        'bad_variable' => 'There was a validation error with the :name variable.',
        'daemon_exception' => 'There was an exception while attempting to communicate with the daemon resulting in a HTTP/:code response code. This exception has been logged. (request id: :request_id)',
        'default_allocation_not_found' => 'The requested default allocation was not found in this server\'s allocations.',
    ],
    'alerts' => [
        'startup_changed' => 'The startup configuration for this server has been updated. If this server\'s egg was changed a reinstall will be occurring now.',
        'server_deleted' => 'Server has successfully been deleted from the system.',
        'server_created' => 'Server was successfully created on the panel. Please allow the daemon a few minutes to completely install this server.',
        'build_updated' => 'The build details for this server have been updated. Some changes may require a restart to take effect.',
        'suspension_toggled' => 'Server suspension status has been changed to :status.',
        'rebuild_on_boot' => 'This server has been marked as requiring a Docker Container rebuild. This will happen the next time the server is started.',
        'install_toggled' => 'The installation status for this server has been toggled.',
        'server_reinstalled' => 'Ce serveur a été mis en file d\'attente pour le début de la réinstallation.',
        'details_updated' => 'Les détails du serveur ont été mis à jour avec succès.',
        'docker_image_updated' => 'Successfully changed the default Docker image to use for this server. A reboot is required to apply this change.',
        'node_required' => 'You must have at least one node configured before you can add a server to this panel.',
        'transfer_nodes_required' => 'You must have at least two nodes configured before you can transfer servers.',
        'transfer_started' => 'Le transfert du serveur a été démarré.',
        'transfer_not_viable' => 'Le nœud que vous avez sélectionné ne dispose pas de l\'espace disque ou de la mémoire nécessaire pour accueillir ce serveur.',
    ],
];
