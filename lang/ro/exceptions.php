<?php

return [
    'daemon_connection_failed' => 'S-a produs o eroare în timpul încercării de a comunica cu demonul, rezultând un cod de răspuns HTTP/:code. Această excepție a fost înregistrată.',
    'node' => [
        'servers_attached' => 'Un nod nu trebuie să aibă servere legate de el pentru a putea fi șters.',
        'daemon_off_config_updated' => 'Configurația demonului <strong>a fost actualizată</strong>, cu toate acestea a fost întâmpinată o eroare în timpul încercării de a actualiza automat fișierul de configurare pe demon. Va trebui să actualizați manual fișierul de configurare (config.yml) pentru demon pentru a aplica aceste modificări.',
    ],
    'allocations' => [
        'server_using' => 'Un server este atribuit în prezent acestei alocări. O alocare poate fi ștearsă doar dacă nu este atribuit niciun server în prezent.',
        'too_many_ports' => 'Adăugarea a mai mult de 1000 de porturi într-un singur interval odată nu este suportată.',
        'invalid_mapping' => 'Maparea furnizată pentru :port a fost invalidă și nu a putut fi procesată.',
        'cidr_out_of_range' => 'Notația CIDR permite doar măști între /25 și /32.',
        'port_out_of_range' => 'Porturile dintr-o alocare trebuie să fie mai mari de 1024 și mai mici sau egale cu 65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'Un Egg cu servere active legate de el nu poate fi șters din Panou.',
        'invalid_copy_id' => 'Egg-ul selectat pentru copierea unui script fie nu există, fie copiază un script în sine.',
        'has_children' => 'Acest Egg este părintele unuia sau mai multor alte Egg-uri. Vă rugăm să ștergeți acele Egg-uri înainte de a șterge acest Egg.',
    ],
    'variables' => [
        'env_not_unique' => 'Variabila de mediu :name trebuie să fie unică pentru acest Egg.',
        'reserved_name' => 'Variabila de mediu :name este protejată și nu poate fi atribuită unei variabile.',
        'bad_validation_rule' => 'Regula de validare ":rule" nu este o regulă validă pentru această aplicație.',
    ],
    'importer' => [
        'json_error' => 'A fost o eroare în timpul încercării de a analiza fișierul JSON: :error.',
        'file_error' => 'Fișierul JSON furnizat nu a fost valid.',
        'invalid_json_provided' => 'Fișierul JSON furnizat nu este într-un format care poate fi recunoscut.',
    ],
    'subusers' => [
        'editing_self' => 'Editarea propriului cont de subutilizator nu este permisă.',
        'user_is_owner' => 'Nu poți adăuga proprietarul serverului ca subutilizator pentru acest server.',
        'subuser_exists' => 'Un utilizator cu acea adresă de e-mail este deja atribuit ca subutilizator pentru acest server.',
    ],
    'databases' => [
        'delete_has_databases' => 'Nu se poate șterge un server gazdă de baze de date care are baze de date active legate de el.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'Timpul maxim de interval pentru o sarcină în lanț este de 15 minute.',
    ],
    'locations' => [
        'has_nodes' => 'Nu se poate șterge o locație care are noduri active legate de ea.',
    ],
    'users' => [
        'node_revocation_failed' => 'Revocarea cheilor pe <a href=":link">Nodul #:node</a> a eșuat. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Nu s-au găsit noduri care să satisfacă cerințele specificate pentru implementarea automată.',
        'no_viable_allocations' => 'Nu s-au găsit alocări care să satisfacă cerințele pentru implementarea automată.',
    ],
    'api' => [
        'resource_not_found' => 'Resursa solicitată nu există pe acest server.',
    ],
];
