<?php

return [
    'daemon_connection_failed' => 'Bandant palaikyti ryšį su daemon įvyko išimtis, dėl kurios buvo gautas HTTP/:code atsakymo kodas. Ši išimtis buvo užregistruota.',
    'node' => [
        'servers_attached' => 'Node neturi turėti nė vieno serverio, kad būtų galima ją ištrinti.',
        'error_connecting' => 'Klaida jungiantis prie „node“',
        'daemon_off_config_updated' => 'Daemon konfiguracija <strong>buvo atnaujinta</strong>, bet įvyko klaida kol bandėme automatiškai atnaujinti konfiguracijos failą Daemon. Jum reikės rankiniu atnaujini konfiguracijos failą(config.yml) Daemon\'ui, kad išsaugoti šiuos pakeitimus.',
    ],
    'allocations' => [
        'server_using' => 'Šiuo metu šiai alokacijai yra priskirtas serveris. Alokaciją galima ištrinti tik tuo atveju, jei šiuo metu jai nėra priskirtas joks serveris.',
        'too_many_ports' => 'Pridėti daugiau nei 1000 prievadų vienu diapazonu yra nepalaikoma.',
        'invalid_mapping' => 'Pateiktas :port atvaizdavimas buvo negaliojantis ir negalėjo būti apdorotas.',
        'cidr_out_of_range' => 'CIDR žymėjimas leidžia naudoti tik kaukes tarp /25 ir /32.',
        'port_out_of_range' => 'Paskirstymo prievadai turi būti didesni arba lygūs 1024 ir mažesni arba lygūs 65535.',
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
        'subuser_exists' => 'Naudotojas su šiuo el. pašto adresu jau egzistuoja kaip subnaudotojas šiam serveriui.',
    ],
    'databases' => [
        'delete_has_databases' => 'Negalite ištrinti databazės pagrindinio serverio, kol ji turi aktyvių databazių prijungtų prie jos.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'Didžiausias intervalo laikas grandininei užduočiai yra 15 minučių.',
    ],
    'locations' => [
        'has_nodes' => 'Negalite ištrinti lokacijos, kuri turi prijungtų aktyvių nodes.',
    ],
    'users' => [
        'is_self' => 'Negalima ištrinti savo naudojo paskyros.',
        'has_servers' => 'Negalima ištrinti naudotojo, prie kurio paskyros prijungti aktyvūs serveriai. Prieš tęsdami, ištrinkite jų serverius.',
        'node_revocation_failed' => 'Nepavyko atšaukti raktų <a href=":link">Node #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Nepavyko rasti nė vieno node, atitinkančio automatiniam diegimui nustatytus reikalavimus.',
        'no_viable_allocations' => 'Automatinio diegimo reikalavimus atitinkančių asignavimų nerasta.',
    ],
    'api' => [
        'resource_not_found' => 'Prašytas resursas neegzistuoja šiame serveryje.',
    ],
    'mount' => [
        'servers_attached' => 'Kad būtų galima ištrinti prijungtą vietą, prie jos neturi būti prijungtų serverių.',
    ],
    'server' => [
        'marked_as_failed' => 'Šis serveris dar nebaigė diegimo proceso, bandykite vėliau.',
    ],
];
