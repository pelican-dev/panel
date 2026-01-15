<?php

return [
    'daemon_connection_failed' => 'Det oppstod en feil under forsøk på å kommunisere med daemonen, noe som resulterte i en HTTP/:code feilkode. Denne feilen har blitt loggført.',
    'node' => [
        'servers_attached' => 'En node må ikke ha noen servere tilknyttet for å kunne slettes.',
        'error_connecting' => 'Feil ved tilkobling til :node',
        'daemon_off_config_updated' => 'Daemon-konfigurasjonen <strong>har blitt oppdatert</strong>, men det oppstod en feil under forsøk på å automatisk oppdatere konfigurasjonsfilen på daemonen. Du må manuelt oppdatere konfigurasjonsfilen (config.yml) for at endringene skal tre i kraft.',
    ],
    'allocations' => [
        'server_using' => 'En server er for øyeblikket tilordnet denne allokeringen. En allokering kan kun slettes hvis ingen server er tilknyttet.',
        'too_many_ports' => 'Det er ikke støttet å legge til mer enn 1000 porter i en enkelt rekke samtidig.',
        'invalid_mapping' => 'Kartleggingen oppgitt for :port var ugyldig og kunne ikke behandles.',
        'cidr_out_of_range' => 'CIDR-notasjon tillater kun masker mellom /25 og /32.',
        'port_out_of_range' => 'Porter i en allokering må være større enn eller lik 1024 og mindre enn eller lik 65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'Et egg med aktive servere tilknyttet kan ikke slettes fra panelet.',
        'invalid_copy_id' => 'Egget som er valgt for å kopiere et skript fra, eksisterer enten ikke eller kopierer allerede et annet skript.',
        'has_children' => 'Dette egget er en overordnet til ett eller flere andre egg. Vennligst slett disse eggene før du sletter dette egget.',
    ],
    'variables' => [
        'env_not_unique' => 'Miljøvariabelen :name må være unik for dette egget.',
        'reserved_name' => 'Miljøvariabelen :name er beskyttet og kan ikke tildeles en variabel.',
        'bad_validation_rule' => 'Valideringsregelen ":rule" er ikke en gyldig regel for denne applikasjonen.',
    ],
    'importer' => [
        'json_error' => 'Det oppstod en feil under forsøk på å analysere JSON-filen: :error.',
        'file_error' => 'Den oppgitte JSON-filen var ikke gyldig.',
        'invalid_json_provided' => 'Den oppgitte JSON-filen er ikke i et format som kan gjenkjennes.',
    ],
    'subusers' => [
        'editing_self' => 'Det er ikke tillatt å redigere din egen underbrukerkonto.',
        'user_is_owner' => 'Du kan ikke legge til servereieren som en underbruker for denne serveren.',
        'subuser_exists' => 'En bruker med den e-postadressen er allerede tilknyttet som en underbruker for denne serveren.',
    ],
    'databases' => [
        'delete_has_databases' => 'Kan ikke slette en databasevert som har aktive databaser tilknyttet.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'Den maksimale intervalltiden for en kjedet oppgave er 15 minutter.',
    ],
    'locations' => [
        'has_nodes' => 'Kan ikke slette en lokasjon som har aktive noder tilknyttet.',
    ],
    'users' => [
        'is_self' => 'Kan ikke slette din egen brukerkonto.',
        'has_servers' => 'Kan ikke slette en bruker med aktive servere tilknyttet kontoen deres. Vennligst slett serverene deres før du fortsetter.',
        'node_revocation_failed' => 'Kunne ikke tilbakekalle nøkler på <a href=":link">Node #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Ingen noder som oppfyller kravene for automatisk distribusjon ble funnet.',
        'no_viable_allocations' => 'Ingen allokeringer som oppfyller kravene for automatisk distribusjon ble funnet.',
    ],
    'api' => [
        'resource_not_found' => 'Den forespurte ressursen eksisterer ikke på denne serveren.',
    ],
    'mount' => [
        'servers_attached' => 'En montering må ikke ha noen servere tilknyttet for å kunne slettes.',
    ],
    'server' => [
        'marked_as_failed' => 'Denne serveren har ikke fullført installasjonsprosessen ennå, prøv igjen senere.',
    ],
];
