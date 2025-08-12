<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Prijava nije uspela',
        'success' => 'Upesno si ulogovan',
        'password-reset' => 'Resetovanje sifre',
        'checkpoint' => 'Zahtevana je dvostruka verifikacija',
        'recovery-token' => 'Iskorišćen token za oporavak iz dvostruke verifikacije',
        'token' => 'Rešena dvostruka verifikacija',
        'ip-blocked' => 'Blokirana zahtev sa neupisane IP adrese za <b>:identifier</b>',
        'sftp' => [
            'fail' => 'Prijava na SFTP nije uspela',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Promenjena E-Pošta sa <b>:old</b> na <b>:new</b>',
            'password-changed' => 'Sifra je promenjena',
        ],
        'api-key' => [
            'create' => 'Kreiran novi API ključ <b>:identifier</b>',
            'delete' => 'Obrisan API ključ <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => 'Dodaj SSH ključ <b>:fingerprint</b> nalogu',
            'delete' => 'Uklonjen je SSH ključ <b>:fingerprint</b> sa naloga',
        ],
        'two-factor' => [
            'create' => 'Uspesno ukljucena 2-Fa autentifikacija',
            'delete' => 'Uspesno iskljucena 2-Fa autentifikacija',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'Izvršen je "<b>:command</b>" na serveru',
        ],
        'power' => [
            'start' => 'Server je pokrenut',
            'stop' => 'Server je zaustavljen',
            'restart' => 'Server je resetovan',
            'kill' => 'Prekinut je proces servera',
        ],
        'backup' => [
            'download' => 'Preuzeta je rezervna kopija <b>:name</b>',
            'delete' => 'Izbrisana je rezervna kopija <b>:name</b>',
            'restore' => 'Restaurirana je rezervna kopija <b>:name</b> (izbrisani fajlovi: <b>:truncate</b>)',
            'restore-complete' => 'Završena restauracija rezervne kopije <b>:name</b>',
            'restore-failed' => 'Nije uspelo završavanje restauracije rezervne kopije <b>:name</b>',
            'start' => 'Započeta je nova rezervna kopija <b>:name</b>',
            'complete' => 'Označena je rezervna kopija <b>:name</b> kao završena',
            'fail' => 'Označena je rezervna kopija <b>:name</b> kao neuspešna',
            'lock' => 'Zaključana je rezervna kopija <b>:name</b>',
            'unlock' => 'Otključana je rezervna kopija <b>:name</b>',
        ],
        'database' => [
            'create' => 'Kreirana nova baza podataka <b>:name</b>',
            'rotate-password' => 'Šifra je promenjena za bazu podataka <b>:name</b>',
            'delete' => 'Obrisana je baza podataka <b>:name</b>',
        ],
        'file' => [
            'compress' => 'Komprimovani su fajlovi <b>:directory:files</b>|Komprimovani su <b>:count</b> fajlovi u <b>:directory</b>',
            'read' => 'Pogledan je sadržaj fajla <b>:file</b>',
            'copy' => 'Kreirana je kopija <b>:file</b>',
            'create-directory' => 'Kreirana je direktorijum <b>:directory:name</b>',
            'decompress' => 'Raspakovano je <b>:file</b> u <b>:directory</b>',
            'delete' => 'Obrisani su <b>:directory:files</b>|Obrisano je <b>:count</b> fajlova u <b>:directory</b>',
            'download' => 'Preuzet je <b>:file</b>',
            'pull' => 'Preuzet je udaljeni fajl sa <b>:url</b> u <b>:directory</b>',
            'rename' => 'Premereno/ Preimenovano <b>:from</b> u <b>:to</b> | Premereno/ Preimenovano <b>:count</b> fajlova u <b>:directory</b>',
            'write' => 'Napisano je novo sadržaj u <b>:file</b>',
            'upload' => 'Započet je otpremanje fajla',
            'uploaded' => 'Otpremio je <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => 'Blokiran SFTP pristup zbog dozvola',
            'create' => 'Kreirani su <b>:files</b>|Kreirano je <b>:count</b> novih fajlova',
            'write' => 'Izmenjeni su sadržaji <b>:files</b>|Izmenjeni su sadržaji <b>:count</b> fajlova',
            'delete' => 'Izbrisan je <b>:files</b>|Izbrisano je <b>:count</b> fajlova',
            'create-directory' => 'Kreiran je <b>:files</b> direktorijum|Kreirano je <b>:count</b> direktorijuma',
            'rename' => 'Preimenovan je <b>:from</b> u <b>:to</b>|Preimenovano ili pomereno je <b>:count</b> fajlova',
        ],
        'allocation' => [
            'create' => 'Dodata je <b>:allocation</b> serveru',
            'notes' => 'Ažurisane su beleške za <b>:allocation</b> sa "<b>:old</b>" na "<b>:new</b>"',
            'primary' => 'Postavljena je <b>:allocation</b> kao glavna alokacija servera',
            'delete' => 'Izbrisana je <b>:allocation</b> alokacija',
        ],
        'schedule' => [
            'create' => 'Kreiran je <b>:name</b> raspored',
            'update' => 'Ažuriran je <b>:name</b> raspored',
            'execute' => 'Ručno je izvršen <b>:name</b> raspored',
            'delete' => 'Izbrisan je <b>:name</b> raspored',
        ],
        'task' => [
            'create' => 'Kreiran je novi zadatak "<b>:action</b>" za <b>:name</b> raspored',
            'update' => 'Ažurisan je zadatak "<b>:action</b>" za <b>:name</b> raspored',
            'delete' => 'Obrisan je zadatak "<b>:action</b>" za raspored <b>:name</b>',
        ],
        'settings' => [
            'rename' => 'Preimenovan je server sa "<b>:old</b>" na "<b>:new</b>"',
            'description' => 'Promenjen je opis servera sa "<b>:old</b>" na "<b>:new</b>"',
            'reinstall' => 'Ponovno instaliran server.',
        ],
        'startup' => [
            'edit' => 'Promenjena je varijabla <b>:variable</b> sa "<b>:old</b>" na "<b>:new</b>"',
            'image' => 'Ažurirana je Docker slika za server sa <b>:old</b> na <b>:new</b>',
        ],
        'subuser' => [
            'create' => 'Dodali ste <b>:email</b> kao podkorisnika',
            'update' => 'Ažurirana su podešavanja dozvola za podkorisnika <b>:email</b>',
            'delete' => 'Uklonjen je <b>:email</b> kao podkorisnik',
        ],
        'crashed' => 'Server je pao',
    ],
];
