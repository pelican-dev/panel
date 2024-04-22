<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'Poskytnutá FQDN neodkazuje na platnú IP adresu.',
        'fqdn_required_for_ssl' => 'Na použitie SSL pre tento uzol je potrebná plnohodnotná doména ukazujúca na verejnú IP adresu.',
    ],
    'notices' => [
        'allocations_added' => 'Alokácie pre tento uzol boli úspešne pridané.',
        'node_deleted' => 'Uzol bol úspešne vymazaný z panelu.',
        'node_created' => 'Nový uzol bol úspešne vytvorený. Daemon na tomto uzle môžete automaticky nakonfigurovať na karte "Konfigurácie". <strong>Pred tým ako pridáte nové servery musíte prideliť aspoň jednu IP adresu a port.</strong>',
        'node_updated' => 'Informácie o uzle boli aktualizované. Ak sa zmenili akékoľvek nastavenia daemonu, budete ho musieť reštartovať na aplikovanie týchto nastavení.',
        'unallocated_deleted' => 'Boli zmazané všetky nepriradené porty pre <code>:ip</code>.',
    ],
];
