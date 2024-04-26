<?php

return [
    'notices' => [
        'imported' => 'Toto vajce a jeho potrebné premenné boli importované úspešne.',
        'updated_via_import' => 'Toto vajce bolo aktualizované pomocou nahraného súboru.',
        'deleted' => 'Požadované vajce bolo úspešne odstránené z panelu.',
        'updated' => 'Konfigurácia vajca bola aktualizovaná úspešne.',
        'script_updated' => 'Inštalačný skript vajca bol aktualizovaný a bude spustený vždy pri inštalácii servera.',
        'egg_created' => 'Nové vajce bolo znesené úspešne. Budete musieť reštartovať spustené daemony na aplikovanie nového vajca.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'Premenná ":variable" bola zmazaná a po prestavaní nebude pre servery dostupná.',
            'variable_updated' => 'Premenná ":variable" bola aktualizovaná. Servery, ktoré používajú danú premennú je potrebné prestavať pre aplikovanie zmien.',
            'variable_created' => 'Nová premenná bola úspešne vytvorená a priradená k tomuto vajcu.',
        ],
    ],
    'descriptions' => [
        'name' => 'A simple, human-readable name to use as an identifier for this Egg.',
        'description' => 'A description of this Egg that will be displayed throughout the Panel as needed.',
        'uuid' => 'This is the globally unique identifier for this Egg which Wings uses as an identifier.',
        'author' => 'The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.',
        'force_outgoing_ip' => "Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.\nRequired for certain games to work properly when the Node has multiple public IP addresses.\nEnabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node.",
        'startup' => 'The default startup command that should be used for new servers using this Egg.',
        'docker_images' => 'The docker images available to servers using this egg.',
    ],
];
