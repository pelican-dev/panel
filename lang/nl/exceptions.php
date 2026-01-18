<?php

return [
    'daemon_connection_failed' => 'Er is een fout opgetreden tijdens het communiceren met de daemon wat resulteert in een HTTP/:code response code. Deze fout is opgeslagen.',
    'node' => [
        'servers_attached' => 'Een node moet geen actieve servers meer hebben voordat deze kan worden verwijderd.',
        'error_connecting' => 'Fout bij het verbinden met',
        'daemon_off_config_updated' => 'De daemonconfiguratie <strong>is bijgewerkt</strong>, er is echter een fout opgetreden bij het automatisch bijwerken van het configuratiebestand op de Daemon. U moet handmatig het configuratiebestand bijwerken (config.yml) voor de daemon om deze veranderingen toe te passen.',
    ],
    'allocations' => [
        'server_using' => 'Een server is momenteel toegewezen aan deze toewijzing. Een toewijzing kan alleen worden verwijderd als er momenteel geen server is toegewezen.',
        'too_many_ports' => 'Meer dan 1000 poorten binnen één bereik toevoegen wordt niet ondersteund.',
        'invalid_mapping' => 'De opgegeven toewijzing voor :port was ongeldig en kon niet worden verwerkt.',
        'cidr_out_of_range' => 'CIDR notatie staat alleen subnet masks toe tussen /25 en /32.',
        'port_out_of_range' => 'De poorten in een toewijzing moeten groter zijn dan 1024 en minder dan of gelijk zijn aan 65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'Een egg met actieve servers gekoppeld kan niet worden verwijderd uit het paneel.',
        'invalid_copy_id' => 'De egg dat geselecteerd is om een script van te kopiëren bestaat niet, of kopieert een script zelf.',
        'has_children' => 'Deze egg is het hoofd van een of meer eggs. Verwijder deze eggs voor het verwijderen van deze egg.',
    ],
    'variables' => [
        'env_not_unique' => 'De omgevingsvariabele :name moet uniek zijn voor deze egg.',
        'reserved_name' => 'De omgevingsvariabele :name is beveiligd en kan niet worden toegewezen aan een variabele.',
        'bad_validation_rule' => 'De validatieregel ":rule" is geen geldige regel voor deze toepassing.',
    ],
    'importer' => [
        'json_error' => 'Er is een fout opgetreden bij het parsen van het JSON-bestand: :error.',
        'file_error' => 'Het opgegeven JSON-bestand is niet geldig.',
        'invalid_json_provided' => 'Het opgegeven JSON-bestand heeft geen formaat dat kan worden herkend.',
    ],
    'subusers' => [
        'editing_self' => 'Het bewerken van uw eigen medegebruikers account is niet toegestaan.',
        'user_is_owner' => 'U kunt niet de servereigenaar toevoegen als een medegebruiker voor deze server.',
        'subuser_exists' => 'Een gebruiker met dit e-mailadres is al toegewezen als medegebruiker voor deze server.',
    ],
    'databases' => [
        'delete_has_databases' => 'Kan geen database host-server verwijderen die actieve databases gelinkt heeft.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'De maximale interval tijd voor een geketende taak is 15 minuten.',
    ],
    'locations' => [
        'has_nodes' => 'Kan een locatie niet verwijderen die actieve nodes heeft gekoppeld.',
    ],
    'users' => [
        'is_self' => 'Je kunt je eigen gebruikersaccount niet verwijderen.',
        'has_servers' => 'De gebruiker kan niet worden verwijderd omdat er actieve servers aan dit account gekoppeld zijn. Gelieve de servers welke gekoppeld zijn aan dit account, te verwijderen voordat je doorgaat.',
        'node_revocation_failed' => 'Intrekken van sleutels op <a href=":link">node #:node</a>is mislukt. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Er konden geen nodes worden gevonden die voldoen aan de vereisten voor automatische implementatie.',
        'no_viable_allocations' => 'Er konden geen nodes worden gevonden die voldoen aan de vereisten voor automatische implementatie.',
    ],
    'api' => [
        'resource_not_found' => 'Het opgevraagde onderdeel bestaat niet op deze server.',
    ],
    'mount' => [
        'servers_attached' => 'Een koppeling mag geen servers eraan gekoppeld hebben om te kunnen worden verwijderd.',
    ],
    'server' => [
        'marked_as_failed' => 'Deze server is nog niet klaar met het installatieproces, probeer het later opnieuw.',
    ],
];
