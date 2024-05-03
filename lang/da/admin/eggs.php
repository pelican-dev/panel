<?php

return [
    'notices' => [
        'imported' => 'Dette Egg og dets tilknyttede variabler blev importeret med succes.',
        'updated_via_import' => 'Dette Egg er blevet opdateret ved hjælp af den givne fil.',
        'deleted' => 'Egget blev slettet fra panelet.',
        'updated' => 'Egget blev opdateret med succes.',
        'script_updated' => 'Eggets installationsscript er blevet opdateret, og vil blive kørt når servere installeres.',
        'egg_created' => 'Et nyt egg blev lagt med succes. Du skal genstarte eventuelle kørende daemons for at anvende dette nye egg.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'Variablen :variable er blevet slettet og vil ikke længere være tilgængelig for servere der er blevet genstartet.',
            'variable_updated' => 'Variablen :variable er blevet opdateret. Du skal genstarte eventuelle servere, der bruger denne variabel for at anvende ændringer.',
            'variable_created' => 'Ny variabel er blevet oprettet og tildelt dette egg.',
        ],
    ],
    'descriptions' => [
        'name' => 'Et simpelt, menneskeligt navn der skal bruges som identifikation for dette Egg.',
        'description' => 'En beskrivelse af dette Egg, der vises i panelet efter behov.',
        'uuid' => 'Dette er den globale unikke identifikator for dette Egg, som Wings bruger som identifikation.',
        'author' => 'The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.',
        'force_outgoing_ip' => "Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.\nRequired for certain games to work properly when the Node has multiple public IP addresses.\nEnabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node.",
        'startup' => 'Standard opstartskommando, som skal bruges til nye servere der gør brug af dette Egg.',
        'docker_images' => 'Docker-billeder tilgængelige for servere, der bruger dette egg.',
    ],
];
