<?php

return [
    'daemon_connection_failed' => 'Došlo je do izuzetka prilikom pokušaja komunikacije sa daemonom, što je rezultiralo HTTP/:code odgovarajućim statusnim kodom. Ovaj izuzetak je zabeležen.',
    'node' => [
        'servers_attached' => 'Čvor ne sme imati servere povezane sa njim, kako bi mogao biti obrisan.',
        'daemon_off_config_updated' => 'Konfiguracija daemona <strong>je ažurirana</strong>, međutim, došlo je do greške prilikom pokušaja automatskog ažuriranja konfiguracione datoteke na Daemonu. Biće potrebno da ručno ažurirate konfiguracionu datoteku (config.yml) kako bi daemon primenio ove promene.',
    ],
    'allocations' => [
        'server_using' => 'Server je trenutno dodeljen ovoj alokaciji. Alokacija može biti obrisana samo ako nijedan server nije trenutno dodeljen.',
        'too_many_ports' => 'Dodavanje više od 1000 portova u jednom opsegu odjednom nije podržano.',
        'invalid_mapping' => 'Mapa pružena za :port je bila nevažeća i nije mogla biti obrađena.',
        'cidr_out_of_range' => 'CIDR notacija dozvoljava maske samo između /25 i /32.',
        'port_out_of_range' => 'Portovi u alokaciji moraju biti veći ili jednaki 1024 i manji ili jednaki 65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'Jaje sa aktivnim serverima povezanim na njega ne može biti obrisano sa Panela.',
        'invalid_copy_id' => 'Jaje koje je odabrano za kopiranje skripte ili ne postoji, ili samo kopira skriptu.',
        'has_children' => 'Ovo Jaje je roditelj jednom ili više drugih Jaja. Molimo vas da obrišete ta Jaja pre nego što obrišete ovo Jaje.',
    ],
    'variables' => [
        'env_not_unique' => 'Varijabla okruženja :name mora biti jedinstvena za ovaj Jaje.',
        'reserved_name' => 'Varijabilno okruženje :name je zaštićena i ne može biti dodeljena varijabili.',
        'bad_validation_rule' => 'Pravilo validacije ":rule" nije važeće pravilo za ovu aplikaciju.',
    ],
    'importer' => [
        'json_error' => 'Došlo je do greške prilikom pokušaja parsiranja JSON fajla: :error.',
        'file_error' => 'JSON fajl koji je dostavljen nije važeći.',
        'invalid_json_provided' => 'JSON fajl koji je dostavljen nije u formatu koji može biti prepoznat.',
    ],
    'subusers' => [
        'editing_self' => 'Izmena vašeg sopstvenog podračuna nije dozvoljena.',
        'user_is_owner' => 'Ne možete dodati vlasnika servera kao podkorisnika za ovaj server.',
        'subuser_exists' => 'Korisnik sa tom e-poštom je već dodeljen kao podkorisnik za ovaj server.',
    ],
    'databases' => [
        'delete_has_databases' => 'Nije moguće obrisati serverskog host baze podataka koji ima povezane aktivne baze podataka.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'Maksimalni interval za povezani zadatak je 15 minuta.',
    ],
    'locations' => [
        'has_nodes' => 'Nije moguće obrisati lokaciju koja ima aktivne čvorove povezane sa njom.',
    ],
    'users' => [
        'is_self' => 'Nije moguće obrisati sopstveni korisnički nalog.',
        'has_servers' => 'Nije moguće obrisati korisnika koji ima aktivne servere povezane sa svojim nalogom. Molimo vas da obrišete njihove servere pre nego što nastavite.',
        'node_revocation_failed' => 'Neuspešno opozivanje ključeva na <a href=":link">Čvor #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Nijedan čvor koji ispunjava specificirane zahteve za automatsko raspoređivanje nije pronađen.',
        'no_viable_allocations' => 'Nijedna alokacija koja ispunjava zahteve za automatsko raspoređivanje nije pronađena.',
    ],
    'api' => [
        'resource_not_found' => 'Traženi resurs ne postoji na ovom serveru.',
    ],
    'mount' => [
        'servers_attached' => 'Da bi montaža bila obrisana, ne sme imati nijedan server povezan sa sobom.',
    ],
    'server' => [
        'marked_as_failed' => 'Ovaj server još uvek nije završio proces instalacije. Molimo pokušajte ponovo kasnije.',
    ],
];
