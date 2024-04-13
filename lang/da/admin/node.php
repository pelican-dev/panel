<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'FQDN eller IP-adressen, der er angivet, resulterer ikke i en gyldig IP-adresse.',
        'fqdn_required_for_ssl' => 'Et fuldt kvalificeret domænenavn, der resulterer i en offentlig IP-adresse, er påkrævet for at bruge SSL til denne node.',
    ],
    'notices' => [
        'allocations_added' => 'Tildelinger er blevet tilføjet til denne node.',
        'node_deleted' => 'Node er blevet slettet fra panelet.',
        'node_created' => 'Ny node blev oprettet. Du kan automatisk konfigurere daemonen på denne maskine ved at besøge \'Configuration\' fanen for denne node. <strong>Før du kan tilføje nogen servere, skal du først tildele mindst en IP-adresse og port.</strong>',
        'node_updated' => 'Node information er blevet opdateret. Hvis nogen daemon indstillinger blev ændret, skal du genstarte den for at anvende disse ændringer.',
        'unallocated_deleted' => 'Slettede alle ikke-tildelte porte for <code>:ip</code>.',
    ],
];
