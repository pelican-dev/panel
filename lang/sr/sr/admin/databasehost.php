<?php

return [
    'nav_title' => 'Hostovi baze podataka',
    'model_label' => 'Host baze podataka',
    'model_label_plural' => 'Hostovi baza podataka',
    'table' => [
        'database' => 'Baza podataka',
        'name' => 'Ime',
        'host' => 'Host',
        'port' => 'Port',
        'name_helper' => 'Ostavi prazno kako bi samo generisalo ime.',
        'username' => 'Korisničko ime',
        'password' => 'Šifra',
        'remote' => 'Konekcija sa',
        'remote_helper' => 'Odakle bi trebalo da budu dozvoljene konekcije. Ostavite prazno da biste dozvolili konekcije sa bilo kog mesta.',
        'max_connections' => 'Maksimalne konekcije',
        'created_at' => 'Kreirano u',
        'connection_string' => 'JDBC konekciona stringa.',
    ],
    'error' => 'Greška prilikom povezivanja sa hostom',
    'host' => 'Host',
    'host_help' => 'IP adresa ili Domen koje treba koristiti prilikom pokušaja povezivanja sa ovim MySQL hostom sa ovog panela za kreiranje novih baza podataka.',
    'port' => 'Port',
    'port_help' => 'Port na kojem MySQL radi za ovaj host.',
    'max_database' => 'Maksimalan broj baza podataka',
    'max_databases_help' => 'Maksimalni broj baza podataka koje mogu biti kreirane na ovom hostu. Ako je limit dostignut, nove baze podataka neće moći da budu kreirane na ovom hostu. Prazno znači neograničeno.',
    'display_name' => 'Prikazano ime',
    'display_name_help' => 'Kratak identifikator koji se koristi za razlikovanje ovog hosta od drugih. Mora biti između 1 i 60 karaktera, na primer, srbija.beograd.lvl3.',
    'username' => 'Korisničko ime',
    'username_help' => 'Korisničko ime naloga koji ima dovoljno dozvola za kreiranje novih korisnika i baza podataka na sistemu.',
    'password' => 'Šifra',
    'password_help' => 'Lozinka za korisnika baze podataka.',
    'linked_nodes' => 'Povezani Čvorovi',
    'linked_nodes_help' => 'Ova postavka se podrazumevano primenjuje na ovaj host baze podataka prilikom dodavanja baze podataka na server na odabranom Čvoru.',
    'connection_error' => 'Greška pri povezivanju na database host',
    'no_database_hosts' => 'Nema hostova baza podataka',
    'no_nodes' => 'Nema Čvorova',
    'delete_help' => 'Database Host Ima Baze Podataka',
    'unlimited' => 'Neograničeno',
    'anywhere' => 'Bilo gde',

    'rotate' => 'Rotiraj',
    'rotate_password' => 'Rotiraj Lozinku',
    'rotated' => 'Šifra je rotirana',
    'rotate_error' => 'Rotiranje šifre nije uspelo',
    'databases' => 'Baze podataka',

    'setup' => [
        'preparations' => 'Pripreme',
        'database_setup' => 'Podešavanje baze podataka',
        'panel_setup' => 'Podešavanje panela',

        'note' => 'Trenutno su podržane samo MySQL/MariaDB baze podataka za hostovanje baze podataka!',
        'different_server' => 'Da li panel i baza podataka <i>nisu</i> na istom serveru?',

        'database_user' => 'Korisnik baze podataka',
        'cli_login' => 'Koristi <code>mysql -u root -p</code> za pristup MySQL komandnoj liniji.',
        'command_create_user' => 'Komanda za kreiranje korisnika',
        'command_assign_permissions' => 'Komanda za dodelu dozvola',
        'cli_exit' => 'Za izlazak iz MySQL komandne linije pokreni <code>exit</code>.',
        'external_access' => 'Spoljni pristup',
        'allow_external_access' => '
                                    <p>Verovatno ćete morati da omogućite spoljni pristup ovoj MySQL instanci kako biste omogućili serverima da se povežu na nju.</p>
                                    <br>
                                    <p>Za to, otvorite <code>my.cnf</code>, čija lokacija zavisi od vašeg operativnog sistema i načina na koji je MySQL instaliran. Možete ukucati <code>find /etc -iname my.cnf</code> da biste je pronašli.</p>
                                    <br>
                                    <p>Otvorite <code>my.cnf</code>, dodajte sledeći tekst na kraj fajla i sačuvajte ga:<br>
                                    <code>[mysqld]<br>bind-address=0.0.0.0</code></p>
                                    <br>
                                    <p>Restartujte MySQL/MariaDB da biste primenili ove promene. Ovo će zameniti podrazumevanu MySQL konfiguraciju, koja po defaultu prihvata samo zahteve sa localhost-a. Ažuriranjem ove postavke omogućite povezivanje na svim interfejsima, pa tako i spoljne veze. Pobrinite se da dozvolite MySQL port (podrazumevano 3306) u vašem firewall-u.</p>                                ',
    ],
];
