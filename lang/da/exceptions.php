<?php

return [
    'daemon_connection_failed' => 'Der opstod en fejl under forsøget på at kommunikere med daemonen, hvilket resulterede i en HTTP/:code responskode. Denne fejl er blevet logget.',
    'node' => [
        'servers_attached' => 'En node må ikke have nogen servere tilknyttet for at kunne slettes.',
        'error_connecting' => 'Fejl ved forbindelse til :node',
        'daemon_off_config_updated' => 'Daemon konfiguration <strong>er blevet opdateret</strong>, men der opstod en fejl under forsøget på automatisk at opdatere konfigurationsfilen på daemonen. Du skal manuelt opdatere konfigurationsfilen (config.yml) for at daemonen kan anvende disse ændringer.',
    ],
    'allocations' => [
        'server_using' => 'En server er i øjeblikket tildelt denne tildeling. En tildeling kan kun slettes, hvis ingen server i øjeblikket er tildelt.',
        'too_many_ports' => 'Tilføjede af flere end 1000 porte i en enkelt række ad gangen understøttes ikke.',
        'invalid_mapping' => 'Den angivne kortlægning for :port var ugyldig og kunne ikke behandles.',
        'cidr_out_of_range' => 'CIDR notation tillader kun masker mellem /25 og /32.',
        'port_out_of_range' => 'Porte i en tildeling skal være større end eller lig med 1024 og mindre end eller lig med 65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'Et æg med aktive servere tilknyttet kan ikke slettes fra panelet.',
        'invalid_copy_id' => 'Ægget valgt til kopiering af et script fra eksisterer ikke, eller kopierer et script selv.',
        'has_children' => 'Dette æg er forælder til et eller flere andre æg. Slet disse æg, før du sletter dette æg.',
    ],
    'variables' => [
        'env_not_unique' => 'Environment variable :name skal være unik for dette æg.',
        'reserved_name' => 'Environment variable :name er beskyttet og kan ikke bruges som en variabel.',
        'bad_validation_rule' => 'Valideringsreglen ":rule" er ikke en gyldig regel for denne applikation.',
    ],
    'importer' => [
        'json_error' => 'Der skete en fejl under forsøget på at parse JSON-filen: :error.',
        'file_error' => 'JSON filen var ikke gyldig.',
        'invalid_json_provided' => 'JSON filen er ikke i et format, der kan genkendes.',
    ],
    'subusers' => [
        'editing_self' => 'Ændring af din egen subbrugerkonto er ikke tilladt.',
        'user_is_owner' => 'Du kan ikke tilføje server ejeren som en subbruger til denne server.',
        'subuser_exists' => 'En bruger med denne e-mailadresse er allerede tildelt som en subbruger til denne server.',
    ],
    'databases' => [
        'delete_has_databases' => 'Du kan ikke slette en database host server, der har aktive databaser tilknyttet.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'Det maksimale interval for en kædet opgave er 15 minutter.',
    ],
    'locations' => [
        'has_nodes' => 'Kan ikke slette en lokation, der har aktive noder tilknyttet.',
    ],
    'users' => [
        'is_self' => 'Du kan ikke slette din egen brugerkonto.',
        'has_servers' => 'Du kan ikke slette en bruger, der har aktive servere tilknyttet sin konto. Slet venligst deres servere, før du fortsætter.',
        'node_revocation_failed' => 'Kunne ikke tilbagekalde nøgler på <a href=":link">Node #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Kunne ikke finde nogle noder, der opfylder kravene for automatisk implementering.',
        'no_viable_allocations' => 'Ingen tildeling, der opfylder kravene for automatisk implementering, blev fundet.',
    ],
    'api' => [
        'resource_not_found' => 'Den anmodede ressource findes ikke på denne server.',
    ],
    'mount' => [
        'servers_attached' => 'Et mount må ikke have nogen servere tilknyttet for at kunne slettes.',
    ],
    'server' => [
        'marked_as_failed' => 'Denne server har endnu ikke gennemført installationsprocessen, prøv venligst igen senere.',
    ],
];
