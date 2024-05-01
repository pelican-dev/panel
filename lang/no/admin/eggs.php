<?php

return [
    'notices' => [
        'imported' => 'Impoterte egget og dens tilknyttede variabler.',
        'updated_via_import' => 'Dette egget har blitt oppdatert med filen oppgitt.',
        'deleted' => 'Egget er slettet fra panelet.',
        'updated' => 'Egg konfigurasjon har blitt oppdatert.',
        'script_updated' => 'Egg installasjonsskriptet har blitt oppdatert og vil kjøre når servere installeres.',
        'egg_created' => 'A new egg was laid successfully. You will need to restart any running daemons to apply this new egg.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'Variabelen ":variable" er slettet og vil ikke lenger være tilgjengelig for serverne når gjenoppbygd.',
            'variable_updated' => 'Variabelen ":variable" er oppdatert. Du må bygge alle servere på nytt med denne variabelen for å aktivere endringene.',
            'variable_created' => 'Ny variabel har blitt opprettet og tildelt dette egget.',
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
