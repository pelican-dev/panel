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
        'port_out_of_range' => 'Porty v alokácii musia mať vyššiu hodnotu ako 1024 a menšiu, alebo rovnú 65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'Vajce s priradenými aktívnymi servermi nemože byť vymazané z panelu.',
        'invalid_copy_id' => 'Vybrané vajce na kopírovanie skriptu buď neexistuje, alebo samé ešte skript kopíruje.',
        'has_children' => 'Toto vajce je rodičom ďalšieho jedného, alebo viacero iných vajec. Prosím zmažte tieto vajcia pred zmazaním tohto vajca.',
    ],
    'variables' => [
        'env_not_unique' => 'Premenná prostredia :name musí byť unikátna tomuto vajcu.',
        'reserved_name' => 'Premenná prostredia :name je chránená a nemôže byť priradená premennej.',
        'bad_validation_rule' => 'Pravidlo validácie ":rule" nieje validné pravidlo pre túto aplikáciu.',
    ],
    'importer' => [
        'json_error' => 'Pri pokuse o analýzu JSON súboru sa vyskytla chyba: :error.',
        'file_error' => 'Poskytnutý JSON súbor nieje validný.',
        'invalid_json_provided' => 'JSON súbor nieje vo formáte, ktorý je možné rozpoznať.',
    ],
    'subusers' => [
        'editing_self' => 'Upravovať vlastného podpoužívateľa nieje povolené.',
        'user_is_owner' => 'Nemôžete pridať majiteľa serveru ako podpoužívateľa pre tento server.',
        'subuser_exists' => 'Používateľov s rovnakou emailovou adresou je už priradený ako podpoužívateľ pre tento server.',
    ],
    'databases' => [
        'delete_has_databases' => 'Nieje možné odstrániť databázový server, ktorý má priradené aktívne databázy.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'Maximálny časový interval pre reťazovú úlohu je 15 minút.',
    ],
    'locations' => [
        'has_nodes' => 'Nieje možné zmazať lokáciu, ktorá má priradené aktívne uzly.',
    ],
    'users' => [
        'node_revocation_failed' => 'Nebolo možné odobrať kľúče na <a href=":link"> Uzol #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'No nodes satisfying the requirements specified for automatic deployment could be found.',
        'no_viable_allocations' => 'No allocations satisfying the requirements for automatic deployment were found.',
    ],
    'api' => [
        'resource_not_found' => 'The requested resource does not exist on this server.',
    ],
];
