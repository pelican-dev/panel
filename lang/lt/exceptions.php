<?php

return [
    'daemon_connection_failed' => 'Bandant palaikyti ryšį su daemon įvyko išimtis, dėl kurios buvo gautas HTTP/:code atsakymo kodas. Ši išimtis buvo užregistruota.',
    'node' => [
        'servers_attached' => 'Node neturi turėti nė vieno serverio, kad būtų galima ją ištrinti.',
        'daemon_off_config_updated' => 'Daemon konfiguracija <strong>buvo atnaujinta</strong>, bet įvyko klaida kol bandėme automatiškai atnaujinti konfiguracijos failą Daemon. Jum reikės rankiniu atnaujini konfiguracijos failą(config.yml) Daemon\'ui, kad išsaugoti šiuos pakeitimus.',
    ],
    'allocations' => [
        'server_using' => 'Šiuo metu šiai alokacijai yra priskirtas serveris. Alokaciją galima ištrinti tik tuo atveju, jei šiuo metu jai nėra priskirtas joks serveris.',
        'too_many_ports' => 'Pridėti daugiau nei 1000 prievadų vienu diapazonu yra nepalaikoma.',
        'invalid_mapping' => 'Pateiktas :port atvaizdavimas buvo negaliojantis ir negalėjo būti apdorotas.',
        'cidr_out_of_range' => 'CIDR žymėjimas leidžia naudoti tik kaukes tarp /25 ir /32.',
        'port_out_of_range' => 'Prievadai alokacijoje turi būti didesni nei 1024 ir mažesni arba lygus 65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'Egg su aktyviais, prijungtais serveriais, negali būti ištrintas iš Panel.',
        'invalid_copy_id' => 'Pasirinktas Egg skripto kopijavimui neegzistuoja arba kopijuoja skriptą pats.',
        'has_children' => 'Šis Egg yra tėvas vienam ar daugiau kitems Eggs. Prašau ištrinti tuos Eggs prieš šio Egg ištrinimą.',
    ],
    'variables' => [
        'env_not_unique' => 'Aplinkos kintamasis :name turi būti unikalus šiam Egg.',
        'reserved_name' => 'Aplinkos kintamasis :name yra apsaugotas ir negali būti paskirtas kintamajam.',
        'bad_validation_rule' => 'Validacijos taisyklė ":rule" nėra galiojanti taisyklė šiai aplikacijai.',
    ],
    'importer' => [
        'json_error' => 'Buvo klaida bandant analizuojant JSON failą: :error.',
        'file_error' => 'Nurodytas JSON failas yra negaliojantis.',
        'invalid_json_provided' => 'Nurodytas JSON failas nėra formatavime, kuris gali būti atpažintas.',
    ],
    'subusers' => [
        'editing_self' => 'Redaguoti savo subnaudotojo paskyrą neleidžiama.',
        'user_is_owner' => 'Jūs negalite pridėti serverio savininko kaip subnaudotojas šiam serveriui.',
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
