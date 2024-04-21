<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'Poskytnutá FQDN neodpovídá platné IP adrese.',
        'fqdn_required_for_ssl' => 'Pro použití SSL pro tento uzel je vyžadován plně kvalifikovaný název domény, který odpovídá veřejné IP adrese',
    ],
    'notices' => [
        'allocations_added' => 'Alokace byly úspěšně přidány do tohoto uzlu.',
        'node_deleted' => 'Uzel byl úspěšně odebrán z panelu.',
        'node_created' => 'Nový uzel byl úspěšně vytvořen. Daemon na tomto uzlu můžete automaticky nakonfigurovat na kartě Konfigurace. <strong>Před přidáním všech serverů musíte nejprve přidělit alespoň jednu IP adresu a port.</strong>',
        'node_updated' => 'Informace o uzlu byly aktualizovány. Pokud bylo změněno nastavení daemonu, budete jej muset restartovat, aby se tyto změny projevily.',
        'unallocated_deleted' => 'Smazány všechny nepřidělené porty pro <code>:ip</code>.',
    ],
];
