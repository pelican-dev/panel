<?php

return [
    'daemon_connection_failed' => 'There was an exception while attempting to communicate with the daemon resulting in a HTTP/:code response code. This exception has been logged.',
    'node' => [
        'servers_attached' => 'A node must have no servers linked to it in order to be deleted.',
        'daemon_off_config_updated' => 'The daemon configuration <strong>has been updated</strong>, however there was an error encountered while attempting to automatically update the configuration file on the Daemon. You will need to manually update the configuration file (config.yml) for the daemon to apply these changes.',
    ],
    'allocations' => [
        'server_using' => 'A server is currently assigned to this allocation. An allocation can only be deleted if no server is currently assigned.',
        'too_many_ports' => 'Adding more than 1000 ports in a single range at once is not supported.',
        'invalid_mapping' => 'The mapping provided for :port was invalid and could not be processed.',
        'cidr_out_of_range' => 'CIDR notation only allows masks between /25 and /32.',
        'port_out_of_range' => 'Porty w alokacji muszą być większe niż 1024 i mniejsze lub równe 65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'Jajo z aktywnymi serwerami przypisanymi do niego nie może zostać usunięte z Panelu.',
        'invalid_copy_id' => 'Jajo wybrane do skopiowania skryptu albo nie istnieje, albo samo posiada kopię skryptu.',
        'has_children' => 'Jajo jest nadrzędne dla jednego lub więcej innych jajek. Proszę najpierw usunąć te jajka przed usunięciem tego.',
    ],
    'variables' => [
        'env_not_unique' => 'Zmienna środowiskowa :name musi być unikalna dla tego jajka.',
        'reserved_name' => 'Zmienna środowiskowa :name jest chroniona i nie może być przypisana do zmiennej.',
        'bad_validation_rule' => 'Reguła walidacji ":rule" nie jest prawidłową regułą dla tej aplikacji.',
    ],
    'importer' => [
        'json_error' => 'Wystąpił błąd podczas próby analizy pliku JSON: :error',
        'file_error' => 'Podany plik JSON jest nieprawidłowy.',
        'invalid_json_provided' => 'Podany plik JSON nie jest w formacie, który może być rozpoznany.',
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
