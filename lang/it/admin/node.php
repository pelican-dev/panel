<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'L\'indirizzo FQDN o IP fornito non si risolve a un indirizzo IP valido.',
        'fqdn_required_for_ssl' => 'Per poter utilizzare SSL per questo nodo è necessario un nome di dominio completamente qualificato che risolva un indirizzo IP pubblico.',
    ],
    'notices' => [
        'allocations_added' => 'Allocazioni aggiunte con successo a questo nodo.',
        'node_deleted' => 'Il nodo è stato rimosso dal pannello.',
        'node_created' => 'Nuovo nodo creato con successo. Puoi configurare automaticamente il demone su questa macchina visitando la scheda \'Configurazione\'. <strong>Prima di poter aggiungere qualsiasi server devi prima assegnare almeno un indirizzo IP e una porta.</strong>',
        'node_updated' => 'Le informazioni sul nodo sono state aggiornate. Se le impostazioni del demone sono state modificate, è necessario riavviarle affinché queste modifiche abbiano effetto.',
        'unallocated_deleted' => 'Eliminato tutte le porte non assegnate per <code>:ip</code>.',
    ],
];
