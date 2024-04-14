<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'Le nom de domaine ou l\'adresse IP fournie ne correspond pas à une adresse IP valide.',
        'fqdn_required_for_ssl' => 'Un nom de domaine qui pointe vers une adresse IP publique est nécessaire pour utiliser SSL sur ce nœud.',
    ],
    'notices' => [
        'allocations_added' => 'Les allocations ont été ajoutées à ce nœud.',
        'node_deleted' => 'Le nœud a été supprimé du panneau avec succès.',
        'node_created' => 'Nouveau nœud créé avec succès. Vous pouvez configurer automatiquement ce dernier sur cette machine en allant dans l\'onglet \'Configuration\'. <strong>Avant de pouvoir ajouter des serveurs, vous devez d\'abord allouer au moins une adresse IP et un port.</strong>',
        'node_updated' => 'Les informations sur le nœud ont été mises à jour. Si des paramètres ont été modifiés, vous devrez le redémarrer le wings pour que ces modifications prennent effet.',
        'unallocated_deleted' => 'Suppression de tous les ports non alloués pour <code>:ip</code>.',
    ],
];
