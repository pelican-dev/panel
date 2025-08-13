<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => 'Navedite E-Poštu sa koje bi "Jaja" izvezena putem ovog Panela trebalo da potiču. Ovo mora biti važeća E-Pošta.',
            'url' => 'URL aplikacije MORA početi sa https:// ili http:// u zavisnosti od toga da li koristite SSL ili ne. Ako ne uključite šemu, vaši emailovi i ostali sadržaji će se povezivati na pogrešnu lokaciju.',
            'timezone' => "Vremenska zona treba da odgovara jednoj od vremenskih zona koje PHP\\'s podržava. Ako niste sigurni, pogledajte https://php.net/manual/en/timezones.php.",
        ],
        'redis' => [
            'note' => 'Odabrali ste Redis drajver za jednu ili više opcija, molimo vas da unesete važeće informacije za povezivanje ispod. U većini slučajeva možete koristiti podrazumevane vrednosti osim ako niste izmenili vašu konfiguraciju.',
            'comment' => 'Podrazumevano Redis instanca servera ima korisničko ime "default" i nema lozinku, jer radi lokalno i nije dostupna spoljnjem svetu. Ako je to slučaj, samo pritisnite Enter bez unošenja vrednosti.',
            'confirm' => 'Izgleda da je :field već definisan za Redis. Da li želite da ga promenite?',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => 'Preporučuje se da ne koristite "localhost" kao domaćina baze podataka jer su uočeni česti problemi sa povezivanjem putem socket-a. Ako želite da koristite lokalnu vezu, trebalo bi da koristite "127.0.0.1".',
        'DB_USERNAME_note' => 'Korišćenje root naloga za MySQL konekcije ne samo da je veoma nepoželjno, već to ova aplikacija i ne dozvoljava. Biće potrebno da kreirate MySQL korisnika specifično za ovaj softver.',
        'DB_PASSWORD_note' => 'Izgleda da već imate definisanu lozinku za MySQL konekciju. Da li želite da je promenite?',
        'DB_error_2' => 'Vaši podaci za povezivanje NISU sačuvani. Morate obezbediti važeće informacije za povezivanje pre nego što nastavite.',
        'go_back' => 'Vratite se nazad i pokušajte ponovo',
    ],
    'make_node' => [
        'name' => 'Unesite kratak identifikator koji će se koristiti za razlikovanje ovog čvora od drugih',
        'description' => 'Unesite opis koji će služiti za identifikaciju ovog čvora',
        'scheme' => 'Molimo unesite https za SSL vezu ili http za vezu bez SSL-a',
        'fqdn' => 'Unesite naziv domena (npr. node.example.com) koji će se koristiti za povezivanje sa demonom. IP adresa može biti korišćena samo ako ne koristite SSL za ovaj čvor',
        'public' => 'Da li ovaj čvor treba da bude javan? Napomena: Ako postavite čvor na privatni režim, onemogućićete opciju automatskog raspoređivanja na ovaj čvor',
        'behind_proxy' => 'Da li je vaš FQDN iza proxy servera?',
        'maintenance_mode' => 'Da li treba da bude omogućen režim održavanja?',
        'memory' => 'Unesite maksimalnu količinu memorije',
        'memory_overallocate' => 'Unesite količinu memorije za prealokaciju, -1 će onemogućiti proveru, a 0 će sprečiti kreiranje novih servera',
        'disk' => 'Unesite maksimalnu količinu prostora na disku',
        'disk_overallocate' => 'Unesite količinu diska za prealokaciju, -1 će onemogućiti proveru, a 0 će sprečiti kreiranje novog servera',
        'cpu' => 'Unesite maksimalnu količinu CPU-a',
        'cpu_overallocate' => 'Unesite količinu CPU-a za prealokaciju, -1 će onemogućiti proveru, a 0 će sprečiti kreiranje novog servera',
        'upload_size' => 'Unesite maksimalnu veličinu fajla za upload',
        'daemonListen' => 'Unesite port na kojem demon sluša',
        'daemonSFTP' => 'Unesite port na kojem demon SFTP sluša',
        'daemonSFTPAlias' => 'Unesite alias za demon SFTP (može ostati prazno)',
        'daemonBase' => 'Unesite osnovni folder',
        'success' => 'Uspešno je kreiran novi čvor sa nazivom :name i ID-om :id',
    ],
    'node_config' => [
        'error_not_exist' => 'Izabrani čvor ne postoji.',
        'error_invalid_format' => 'Navedeni format je nevažeći. Važeće opcije su yaml i json.',
    ],
    'key_generate' => [
        'error_already_exist' => 'Izgleda da ste već konfigurisali ključ za šifrovanje aplikacije. Nastavljanje ovog procesa će prepisati taj ključ i izazvati korupciju podataka za sve postojeće šifrovane podatke. NEMOJTE NASTAVITI OSIM AKO ZNATE ŠTA RADITE.',
        'understand' => 'Razumem posledice izvršavanja ove komande i prihvatam svu odgovornost za gubitak šifrovanih podataka.',
        'continue' => 'Da li ste sigurni da želite da nastavite? Promena ključa za šifrovanje aplikacije ĆE IZAZVATI GUBITAK PODATAKA.',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => 'Nema zakazanih zadataka za servere koje je potrebno izvršiti.',
            'error_message' => 'Došlo je do greške prilikom obrade rasporeda: ',
        ],
    ],
    'upgrade' => [
        'integrity' => 'Ova komanda ne proverava integritet preuzetih podataka. Molimo vas da se uverite da verujete izvoru preuzimanja pre nego što nastavite. Ako ne želite da preuzmete arhivu, navedite to pomoću opcije --skip-download ili odgovorite "ne" na pitanje ispod.',
        'source_url' => 'Preuzimanje izvora (postavite pomoću --url=):',
        'php_version' => 'Ne mogu da izvršim proces samostalnog ažuriranja. Minimalna zahtevana verzija PHP-a je 7.4.0, vi imate',
        'skipDownload' => 'Da li želite da preuzmete i raspakujete arhivske fajlove za najnoviju verziju?',
        'webserver_user' => 'Vaš korisnik web servera je detektovan kao <fg=blue>[{:user}]:</> Da li je ovo tačno?',
        'name_webserver' => 'Molimo vas unesite ime korisnika koji pokreće proces vašeg veb servera. Ovo varira od sistema do sistema, ali obično je "www-data", "nginx" ili "apache".',
        'group_webserver' => 'Vaša grupa web servera je detektovana kao <fg=blue>[{:group}]:</> Da li je ovo tačno?',
        'group_webserver_question' => 'Molimo vas unesite ime grupe koja pokreće proces vašeg web servera. Obično je ovo isto kao i ime korisnika.',
        'are_your_sure' => 'Da li ste sigurni da želite da pokrenete proces nadogradnje vašeg Panela?',
        'terminated' => 'Proces nadogradnje je prekinut od strane korisnika.',
        'success' => 'Panel je uspešno nadograđen. Molimo vas da se uverite da ste takođe ažurirali sve instance Demona.',

    ],
];
