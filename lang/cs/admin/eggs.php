<?php

return [
    'notices' => [
        'imported' => 'Úspěšně importováno toto vejce a jeho související proměnné.',
        'updated_via_import' => 'Toto vejce bylo aktualizováno pomocí poskytnutého souboru.',
        'deleted' => 'Požadované vejce bylo úspěšně smazáno z panelu.',
        'updated' => 'Konfigurace vejce byla úspěšně aktualizována.',
        'script_updated' => 'Instalační skript vejce byl aktualizován a bude spuštěn vždy, když budou nainstalovány servery.',
        'egg_created' => 'Nové vejce bylo úspěšně přidáno. Abyste mohli použít toto nové vejce, budete muset restartovat všechny spuštěné daemony.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'Proměnná „:variable“ byla odstraněna a nebude serverům po rekonstrukci k dispozici.',
            'variable_updated' => 'Proměnná „:variable“ byla aktualizována. Budete muset obnovit všechny servery používající tuto proměnnou pro použití změn.',
            'variable_created' => 'Nová proměnná byla úspěšně vytvořena a přiřazena k tomuto vejci.',
        ],
    ],
    'descriptions' => [
        'name' => 'Jednoduchý, člověkem čitelný název pro toto vejce.',
        'description' => 'Popis tohoto vejce, který se zobrazí v panelu podle potřeby.',
        'uuid' => 'Toto je celosvětově jedinečný identifikátor pro tento Egg, který Wings používá jako identifikátor.',
        'author' => 'Autor této verze Egg. Nahrání nové konfigurace Egg od jiného autora to změní.',
        'force_outgoing_ip' => "Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.\nRequired for certain games to work properly when the Node has multiple public IP addresses.\nEnabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node.",
        'startup' => 'The default startup command that should be used for new servers using this Egg.',
        'docker_images' => 'The docker images available to servers using this egg.',
    ],
];
