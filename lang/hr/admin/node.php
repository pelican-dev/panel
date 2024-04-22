<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'Navedeni FQDN ili IP adresa nema valjanu IP adresu.',
        'fqdn_required_for_ssl' => 'Za korištenje SSL-a za ovaj node potreban je FQDN koji se pretvara u javnu IP adresu.',
    ],
    'notices' => [
        'allocations_added' => 'Portovi su uspješno dodane ovom čvoru.',
        'node_deleted' => 'Node je uspješno izbrisan sa panela.',
        'node_created' => 'Uspješno ste napravili novi node. Možete automatski konfigurirati daemon na toj mašini ako posjetite \'Konfiguriracija\' karticu.
<strong>Prije nego što napravite servere morate prvo dodijeliti barem jednu IP adresu i port.</strong>',
        'node_updated' => 'Node informacije su uspješno ažurirane. Ako su neke daemon postavke promjenjene morate ponovno pokreniti kako bi se promjene primjenile.',
        'unallocated_deleted' => 'Izbriši sve ne-nekorištene portovi za <code>:ip</code>.',
    ],
];
