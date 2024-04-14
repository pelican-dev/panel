<?php

return [
    'daemon_connection_failed' => 'Byla zaznamenána nečekaná vyjímka při pokusu komunikovat s daemonem vyusaťující v chybu HTTP/:code. Tahle vyjímka byla logována.',
    'node' => [
        'servers_attached' => 'Uzel nesmí mít žádné s ním spojené servery, aby mohl být smazán',
        'daemon_off_config_updated' => 'Konfigurace daemonu <strong>byla aktualizována</strong>, ale byla zde chyba při automatické aktualizaci souborů konfigurace Daemonu. Je třeba soubory konfigurace Daemonu aktualizovat manuálně (config.yml), aby změny damemonu byly aplikovány.',
    ],
    'allocations' => [
        'server_using' => 'Server již využívá tuhle alokaci. Pro odstranění alokace, nesmí být žádný server spojen s alokací.',
        'too_many_ports' => 'Přidávat více než 1000 portů v jednom rozsahu není podporováno.',
        'invalid_mapping' => 'Mapování poskytnuto pro port :port bylo nesprávné a nebylo možné ho zpracovat.',
        'cidr_out_of_range' => 'CIDR zápis je možný jen pro masky /25 až /32 subnetu.',
        'port_out_of_range' => 'Porty v alokacích musí být vyšší než 1024 a nížší nebo se rovnat 65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'Egg s aktivními servery není možné smazat z panelu.',
        'invalid_copy_id' => 'Zvolený egg na kopii skriptu buď neexistuje nebo neobsahuje samotný skript.',
        'has_children' => 'Tento egg je parent jednoho či více eggů. Prosím vymažte tyto eggy předtím než smažete tento.',
    ],
    'variables' => [
        'env_not_unique' => 'Proměnná prostředí :name musí mít unikátní pro tento egg.',
        'reserved_name' => 'Proměnná prostředí :name je chráněna a nemůže být přidělena k této proměnné',
        'bad_validation_rule' => 'The validation rule ":rule" is not a valid rule for this application. Ověřovací pravidlo ":rule" není platné pravidlo pro toto využití/aplikaci.',
    ],
    'importer' => [
        'json_error' => 'Byla zde chyba při pokusu převodu (parsing) JSON souboru: :error.',
        'file_error' => 'Poskytnutý JSON soubor není platný.',
        'invalid_json_provided' => 'Formát poskytnutého JSON souboru nebylo možné rozeznat',
    ],
    'subusers' => [
        'editing_self' => 'Úprava tvého vlastního podúčtu není dovolena.',
        'user_is_owner' => 'Nemůžeš přidat nového vlastníka jako poduživatel pro tento server.',
        'subuser_exists' => 'Uživatel s touto emailovou adresou je již poduživatel na tomto serveru.',
    ],
    'databases' => [
        'delete_has_databases' => 'Nelze odstranit hostitelský server databáze, který má s ním spojené aktivní databáze.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'Maximální čas intervalu pro tuto řetězovou úlohu je 15 minut.',
    ],
    'locations' => [
        'has_nodes' => 'Nelze smazat lokaci, která má s ní spojené aktivní uzly.',
    ],
    'users' => [
        'node_revocation_failed' => 'Odstranění klíču pro uzel <a href=":link">Node #:node</a> nevyšlo: :error.',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Žadné uzly nesplňují specifikované požadavky pro automatické aplikování.',
        'no_viable_allocations' => 'Žádné alokace nesplňující požadavky pro automatickou aplikaci nebyly nalezeny.',
    ],
    'api' => [
        'resource_not_found' => 'Požadováný zdroj//prostředí (resource) neexistuje v tomto server.',
    ],
];
