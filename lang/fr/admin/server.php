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
        'server_created' => 'Le serveur a été créé avec succès. Veuillez patienter quelques minutes avant l\'installation complète du serveur.',
        'build_updated' => 'Les détails du build de ce serveur ont été mis à jour. Certains changements peuvent nécessiter un redémarrage pour prendre effet.',
        'suspension_toggled' => 'Le statut de suspension du serveur a été changé à :status.',
        'rebuild_on_boot' => 'Ce serveur a été marqué comme nécessitant une reconstruction du conteneur Docker. Cela se produira la prochaine fois que le serveur sera démarré.',
        'install_toggled' => 'Le status d\'installation de ce serveur à bien été basculé',
        'server_reinstalled' => 'Ce serveur a été mis en file d\'attente pour le début de la réinstallation.',
        'details_updated' => 'Les détails du serveur ont été mis à jour avec succès.',
        'docker_image_updated' => 'L\'image Docker par défaut a été modifiée avec succès pour ce serveur. Un redémarrage est nécessaire pour appliquer cette modification.',
        'node_required' => 'Vous devez avoir au moins un nœud configuré avant de pouvoir ajouter des serveurs à ce panel.',
        'transfer_nodes_required' => 'Vous devez avoir au moins deux nœuds configurés avant de pouvoir transférer des serveurs.',
        'transfer_started' => 'Le transfert du serveur a été démarré.',
        'transfer_not_viable' => 'Le nœud que vous avez sélectionné ne dispose pas de l\'espace disque ou de la mémoire nécessaire pour accueillir ce serveur.',
    ],
];
