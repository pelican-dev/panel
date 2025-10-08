<?php

return [
    'daemon_connection_failed' => 'A existat o excepție în timpul încercării de a comunica cu daemonul rezultând într-un cod de răspuns HTTP/:code. Această excepție a fost înregistrată.',
    'node' => [
        'servers_attached' => 'Un nod nu trebuie să aibă servere conectate la el pentru a putea fi șters.',
        'error_connecting' => 'Eroare de conectare la nod',
        'daemon_off_config_updated' => 'Configurația daemon <strong>a fost actualizată</strong>, cu toate acestea, a apărut o eroare la încercarea de a actualiza automat fișierul de configurare din Daemon. Va trebui să actualizați manual fișierul de configurare (config.yml) pentru ca daemonul să aplice aceste schimbări.',
    ],
    'allocations' => [
        'server_using' => 'Un server este în prezent atribuit acestei alocări. O alocare poate fi ștearsă numai dacă nici un server nu este atribuit momentan.',
        'too_many_ports' => 'Adăugarea simultană a mai mult de 1000 de porturi într-o singură cerere nu este acceptată.',
        'invalid_mapping' => 'Maparea furnizată pentru :port nu a fost validă și nu a putut fi procesată.',
        'cidr_out_of_range' => 'Notația CIDR permite masca doar între /25 și /32.',
        'port_out_of_range' => 'Sumele alocate porturilor trebuie să fie mai mari sau egale cu 1024 şi mai mici sau egale cu 65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'Un ou cu servere active atașate nu poate fi șters din Panou.',
        'invalid_copy_id' => 'Oul selectat pentru copierea unui script fie nu există, fie copiază un script în sine.',
        'has_children' => 'Acest ou este un părinte pentru unul sau mai multe alte ouă. Vă rugăm să ștergeți acele ouă înainte de a șterge acest ou.',
    ],
    'variables' => [
        'env_not_unique' => 'Variabila de mediu :name trebuie să fie unică pentru acest ou.',
        'reserved_name' => 'Variabila de mediu :name este protejată și nu poate fi atribuită unei variabile.',
        'bad_validation_rule' => 'Regula de validare ":rule" nu este o regulă validă pentru această aplicație.',
    ],
    'importer' => [
        'json_error' => 'A apărut o eroare la analizarea fișierului JSON: :error.',
        'file_error' => 'Fișierul JSON furnizat nu este valid.',
        'invalid_json_provided' => 'Fișierul JSON furnizat nu este într-un format care poate fi recunoscut.',
    ],
    'subusers' => [
        'editing_self' => 'Editarea propriului cont de subuser nu este permisă.',
        'user_is_owner' => 'Nu puteți adăuga proprietarul serverului ca subuser pentru acest server.',
        'subuser_exists' => 'Un utilizator cu această adresă de e-mail este deja atribuit ca subuser pentru acest server.',
    ],
    'databases' => [
        'delete_has_databases' => 'Nu se poate șterge un server gazdă de baze de date care are baze de date active conectate la acesta.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'Intervalul maxim de timp pentru o sarcină legată este de 15 minute.',
    ],
    'locations' => [
        'has_nodes' => 'Nu se poate șterge o locație care are noduri active atașate la ea.',
    ],
    'users' => [
        'is_self' => 'Nu puteți șterge propriul cont de utilizator.',
        'has_servers' => 'Nu se poate șterge un utilizator cu servere active atașate la contul său. Vă rugăm să ștergeți serverele lor înainte de a continua.',
        'node_revocation_failed' => 'Nu s-au putut revoca cheile de pe <a href=":link">Nodul #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Nu a putut fi găsit niciun nod care să satisfacă cerințele specificate pentru deploy-ul automat.',
        'no_viable_allocations' => 'Nu a putut fi găsită nicio alocare care să satisfacă cerințele specificate pentru deploy-ul automat.',
    ],
    'api' => [
        'resource_not_found' => 'Resursa solicitată nu există pe acest server.',
    ],
    'mount' => [
        'servers_attached' => 'O montare nu trebuie să aibă servere atașate la ea pentru a fi ștearsă',
    ],
    'server' => [
        'marked_as_failed' => 'Acest server nu a finalizat încă procesul de instalare, vă rugăm să încercaţi din nou mai târziu.',
    ],
];
