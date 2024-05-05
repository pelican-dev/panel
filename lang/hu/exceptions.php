<?php

return [
    'daemon_connection_failed' => 'Probléma lépett fel a daemonnal való kommunikáció során a HTTP/:code válasz kód alatt. Naplózásra került a probléma.',
    'node' => [
        'servers_attached' => 'A node nem tartalmazhat szervereket a törlés végrehajtásához.',
        'daemon_off_config_updated' => 'A daemon konfiguráció<strong>frissítve</strong>, azonban probléma lépett fel a daemon automatikus konfiguráció frissítése során. Manuálisan kell frissítened a konfigurációs fájlt (config.yml) hogy életbe lépjenek a daemonon végzett módosítások.',
    ],
    'allocations' => [
        'server_using' => 'Ez a szerver társított egy allokációhoz, csak akkor törölhetsz egy allokációt ha ahhoz nincsen szerver társítva.',
        'too_many_ports' => 'Több mint 1000 port megadása egy megadott tartományban nem támogatott.',
        'invalid_mapping' => 'A következő porthoz érvénytelen a hozzárendelés és nem sikerült feldolgozni: {port}.',
        'cidr_out_of_range' => 'A CIDR maszk csak /25 és /32es tartomány között engedélyezett.',
        'port_out_of_range' => 'A kiosztásban lévő portoknak 1024-nél nagyobbaknak és 65535-nél kisebbnek vagy azzal egyenlőnek kell lenniük.',
    ],
    'egg' => [
        'delete_has_servers' => 'Egy aktív szerverhez társított Egg nem törölhető a panelből.',
        'invalid_copy_id' => 'A script másolására kiválasztott Egg nem létezik, vagy magát a scriptet másolja.',
        'has_children' => 'This Egg is a parent to one or more other Eggs. Please delete those Eggs before deleting this Egg.',
    ],
    'variables' => [
        'env_not_unique' => 'The environment variable :name must be unique to this Egg.',
        'reserved_name' => 'The environment variable :name is protected and cannot be assigned to a variable.',
        'bad_validation_rule' => 'The validation rule ":rule" is not a valid rule for this application.',
    ],
    'importer' => [
        'json_error' => 'There was an error while attempting to parse the JSON file: :error.',
        'file_error' => 'The JSON file provided was not valid.',
        'invalid_json_provided' => 'The JSON file provided is not in a format that can be recognized.',
    ],
    'subusers' => [
        'editing_self' => 'Editing your own subuser account is not permitted.',
        'user_is_owner' => 'You cannot add the server owner as a subuser for this server.',
        'subuser_exists' => 'A user with that email address is already assigned as a subuser for this server.',
    ],
    'databases' => [
        'delete_has_databases' => 'Cannot delete a database host server that has active databases linked to it.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'The maximum interval time for a chained task is 15 minutes.',
    ],
    'locations' => [
        'has_nodes' => 'Cannot delete a location that has active nodes attached to it.',
    ],
    'users' => [
        'node_revocation_failed' => 'Failed to revoke keys on <a href=":link">Node #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'No nodes satisfying the requirements specified for automatic deployment could be found.',
        'no_viable_allocations' => 'No allocations satisfying the requirements for automatic deployment were found.',
    ],
    'api' => [
        'resource_not_found' => 'The requested resource does not exist on this server.',
    ],
];
