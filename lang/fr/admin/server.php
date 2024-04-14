<?php

return [
    'exceptions' => [
        'no_new_default_allocation' => 'Vous essayez de supprimer l\'allocation par défaut pour ce serveur, mais il n\'y a pas d\'allocation de secours à utiliser.',
        'marked_as_failed' => 'Ce serveur a été marqué comme ayant échoué à l\'installation précédente. Le statut actuel ne peut pas être basculé dans cet état.',
        'bad_variable' => 'Il y a eu une erreur de validation avec la variable :name.',
        'daemon_exception' => 'Une erreur est survenue lors de la tentative de communication avec le démon, entraînant un code de réponse HTTP/:code. Cette exception a été enregistrée. (request id: :request_id)',
        'default_allocation_not_found' => 'L\'allocation par défaut demandée n\'a pas été trouvée dans les allocations de ce serveur.',
    ],
    'alerts' => [
        'startup_changed' => 'La configuration de démarrage de ce serveur a été mise à jour. Si l\'œuf de ce serveur a été modifié, une réinstallation aura lieu maintenant.',
        'server_deleted' => 'Le serveur a été supprimé du système avec succès.',
        'server_created' => 'Server was successfully created on the panel. Please allow the daemon a few minutes to completely install this server.',
        'build_updated' => 'The build details for this server have been updated. Some changes may require a restart to take effect.',
        'suspension_toggled' => 'Le statut de suspension du serveur a été changé à :status.',
        'rebuild_on_boot' => 'This server has been marked as requiring a Docker Container rebuild. This will happen the next time the server is started.',
        'install_toggled' => 'The installation status for this server has been toggled.',
        'server_reinstalled' => 'Ce serveur a été mis en file d\'attente pour le début de la réinstallation.',
        'details_updated' => 'Les détails du serveur ont été mis à jour avec succès.',
        'docker_image_updated' => 'L\'image Docker par défaut a été modifiée avec succès pour ce serveur. Un redémarrage est nécessaire pour appliquer cette modification.',
        'node_required' => 'Vous devez avoir au moins un nœud configuré avant de pouvoir ajouter des serveurs à ce panel.',
        'transfer_nodes_required' => 'Vous devez avoir au moins deux nœuds configurés avant de pouvoir transférer des serveurs.',
        'transfer_started' => 'Le transfert du serveur a été démarré.',
        'transfer_not_viable' => 'Le nœud que vous avez sélectionné ne dispose pas de l\'espace disque ou de la mémoire nécessaire pour accueillir ce serveur.',
    ],
];
