<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'Le numéro FQDN ou l\'adresse IP fournie ne correspond pas à une adresse IP valide.',
        'fqdn_required_for_ssl' => 'A fully qualified domain name that resolves to a public IP address is required in order to use SSL for this node.',
    ],
    'notices' => [
        'allocations_added' => 'Les allocations ont été ajoutées à ce noeud.',
        'node_deleted' => 'Le noeud a été supprimé du panneau avec succès.',
        'node_created' => 'Successfully created new node. You can automatically configure the daemon on this machine by visiting the \'Configuration\' tab. <strong>Before you can add any servers you must first allocate at least one IP address and port.</strong>',
        'node_updated' => 'Les informations sur le noeud ont été mises à jour. Si des paramètres ont été modifiés, vous devrez le redémarrer le wings pour que ces modifications prennent effet.',
        'unallocated_deleted' => 'Suppression de tous les ports non alloués pour <code>:ip</code>.',
    ],
];
