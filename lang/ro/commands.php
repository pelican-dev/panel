<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => 'Furnizați adresa de e-mail de la care ar trebui să fie exportate ouăle prin acest Panou. Aceasta ar trebui să fie o adresă de e-mail validă.',
            'url' => 'Aplicaţia URL TREBUIE să înceapă cu http:// sau http:// în funcţie de dacă utilizaţi SSL sau nu. Dacă nu includeți schema de e-mailuri și alte conținuturi se vor conecta la locația greșită.',
            'timezone' => 'Fusul orar ar trebui să se potrivească cu unul dintre fusele orare acceptate de PHP. Dacă nu sunteți sigur, vă rugăm să faceți referire la\\ https://php.net/manual/en/timezones.php.',
        ],
        'redis' => [
            'note' => 'Ați selectat driverul Redis pentru una sau mai multe opțiuni, vă rugăm să furnizați informații valide de conectare de mai jos. În cele mai multe cazuri puteți utiliza valorile implicite furnizate, cu excepția cazului în care ați modificat configurarea.',
            'comment' => 'În mod implicit, o instanță de server Redis are pentru numele de utilizator implicit și nicio parolă deoarece rulează local și este inaccesibilă pentru lumea din afară. În acest caz, apăsați butonul de intrare fără a introduce o valoare.',
            'confirm' => 'Se pare că un  :field este deja definit pentru Redis, doriți să îl schimbați?',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => 'Este recomandat sa nu se foloseasca "localhost" ca gazda ta de baza de date pentru ca am vazut probleme frecvente legate de conexiunea socket-ului. Dacă doriți să utilizați o conexiune locală, ar trebui să utilizați "127.0.0.1".',
        'DB_USERNAME_note' => 'Folosirea contului root pentru conexiunile MySQL nu este doar foarte înspăimântată, dar nici nu este permisă de această aplicație. Va trebui să vă creați un utilizator MySQL pentru acest software.',
        'DB_PASSWORD_note' => 'Se pare ca aveti deja o parola pentru conexiunea MySQL, doriti sa o schimbati?',
        'DB_error_2' => 'Datele de conectare NU au fost salvate. Va trebui să furnizați informații valide despre conexiune înainte de a continua.',
        'go_back' => 'Întoarce-te și încearcă din nou',
    ],
    'make_node' => [
        'name' => 'Introduceți un identificator scurt folosit pentru a distinge acest modul de altele',
        'description' => 'Introduceți o descriere pentru a identifica nodul',
        'scheme' => 'Vă rugăm să introduceți https pentru SSL sau http pentru o conexiune non-ssl',
        'fqdn' => 'Introduceți un nume de subdomeniu (de ex. node.example.com) pentru a fi utilizat pentru conectarea la daemon. O adresă IP poate fi utilizată numai dacă nu utilizați SSL pentru acest modul',
        'public' => 'Ar trebui ca acest nod să fie public? Ca o notă, setând un nod la privat veți nega capacitatea de auto-implementare la acest modul.',
        'behind_proxy' => 'Este FQDN în spatele unui proxy? (Exemplu: CloudFlare)',
        'maintenance_mode' => 'Ar trebui activat modul de mentenanță?',
        'memory' => 'Introduceţi numărul maxim de memorie',
        'memory_overallocate' => 'Introduceți memoria peste alocare, -1 va dezactiva verificarea și 0 va împiedica crearea de noi servere',
        'disk' => 'Introduceți limita maximă de spațiu pe disc',
        'disk_overallocate' => 'Introduceți cantitatea de disc pentru a fi suprasolicitată de, -1 va dezactiva verificarea și 0 va împiedica crearea unui nou server',
        'cpu' => 'Introduceți valoarea maximă a cpu',
        'cpu_overallocate' => 'Introduceți cantitatea de disc pentru a fi suprasolicitată, -1 va dezactiva verificarea și 0 va împiedica crearea unui nou server',
        'upload_size' => 'Introduceți dimensiunea maximă a fișierului',
        'daemonListen' => 'Introduceți portul de ascultare al daemon-ului',
        'daemonConnect' => 'Introdu portul de conectare al daemon-ului (poate fi același ca portul de ascultare)',
        'daemonSFTP' => 'Introduceți portul de ascultare al daemon-ului pentru SFTP',
        'daemonSFTPAlias' => 'Introduceți aliasul pentru SFTP-ul daemon-ului (poate fi lăsat liber)',
        'daemonBase' => 'Introduceți directorul de bază',
        'success' => 'A fost creat cu succes un nod nou cu numele :name și are id-ul :id',
    ],
    'node_config' => [
        'error_not_exist' => 'Nodul selectat nu există.',
        'error_invalid_format' => 'Format invalid specificat. Opţiunile valide sunt yaml şi json.',
    ],
    'key_generate' => [
        'error_already_exist' => 'Se pare că aţi configurat deja o cheie de criptare a aplicaţiei. Continuarea acestui proces cu suprascrierea acelei chei și cauzează corupție de date pentru orice date criptate existente. NU CONTINUAȚI DECÂT DACĂ ȘTIȚI BINE CE FACEȚI',
        'understand' => 'Înțeleg consecințele efectuării acestei comenzi și accept toată responsabilitatea pentru pierderea datelor criptate.',
        'continue' => 'Sunteți sigur că doriți să continuați? Schimbarea cheii de criptare a aplicației VA CAUZA PIERDERI DE DATE',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => 'Nu există sarcini programate pentru servere care trebuie să fie rulate.',
            'error_message' => 'A apărut o eroare la procesarea sarcinii: ',
        ],
    ],
];
