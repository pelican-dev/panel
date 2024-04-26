<?php

return [
    'notices' => [
        'imported' => 'Sikeresen importáltad ezt az Egg-et és a hozzátartozó változókat.',
        'updated_via_import' => 'Ez az Egg frissítve lett a megadott fájl segítségével.',
        'deleted' => 'Sikeresen törölted a kívánt Egg-et a panelből.',
        'updated' => 'Az Egg konfigurációja sikeresen frissítve lett.',
        'script_updated' => 'Az Egg telepítési scriptje frissítve lett, és a szerver telepítésekor lefut.',
        'egg_created' => 'Sikeresen hozzá adtál egy új egg-et. Újra kell indítanod minden futó daemon-t az Egg alkalmazásához.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'A ":változó" változó törlésre került, és az újratelepítés után már nem lesz elérhető a szerverek számára.',
            'variable_updated' => 'A ":változó" változót frissítettük. A változások alkalmazásához újra kell telepítenie az ezt a változót használó szervereket.',
            'variable_created' => 'Az új változót sikeresen létrehoztuk és hozzárendeltük ehhez az Egg-hez.',
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
