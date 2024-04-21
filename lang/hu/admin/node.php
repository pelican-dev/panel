<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'A megadott FQDN vagy IP-cím nem felel meg érvényes IP-címnek!',
        'fqdn_required_for_ssl' => 'Az SSL használatához ehhez a csomóponthoz egy teljesen minősített tartománynévre van szükség, amely nyilvános IP-címet eredményez!',
    ],
    'notices' => [
        'allocations_added' => 'Sikeresen hozzáadtad az allokációkat ehhez a node-hoz!',
        'node_deleted' => 'Sikeresen törölted a node-ot!',
        'node_created' => 'Sikeresen létrehoztál egy új node-ot. A daemon-t automatikusan konfigurálhatod a "Konfiguráció" fülön. <strong>Mielőtt új szervert készítenél, legalább egy IP címet és portot kell allokálnod.</strong>',
        'node_updated' => 'Node információk frissítve. Ha a daemon beállításait módosítottad, újra kell indítani a daemont a módosítások érvénybe léptetéséhez!',
        'unallocated_deleted' => 'Törölted a <code>:ip</code> összes ki nem osztott portját!',
    ],
];
