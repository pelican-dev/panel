<?php

return [
    'nav_title' => 'Duomenų bazės serveriai',
    'model_label' => 'Duomenų bazės serveris',
    'model_label_plural' => 'Duomenų bazės serveriai',
    'table' => [
        'database' => 'Duomenų bazė',
        'name' => 'Pavadinimas',
        'host' => 'Serverio IP',
        'port' => 'Prievadas',
        'name_helper' => 'Palikus tuščią, bus automatiškai sugeneruotas atsitiktinis pavadinimas',
        'username' => 'Vartotojo vardas',
        'password' => 'Slaptažodis',
        'remote' => 'Prisijungimai iš',
        'remote_helper' => 'Iš kur būtų leidžiama prisijungti. Palikite tuščią, jeigu norite leisti jungtis iš bet kur.',
        'max_connections' => 'Didžiausia riba prisijungimams',
        'created_at' => 'Sukūrimo data',
        'connection_string' => 'JDBC prisijungimo tekstas',
    ],
    'error' => 'Klaida jungiantis prie serverio',
    'host' => 'Serverio IP',
    'host_help' => 'IP adresas arba domeno vardas, kuris turėtų būti naudojamas bandant prisijungti prie šio „MySQL“ serverio iš šio valdymo punkto, kad būtų sukurtos naujos duomenų bazės.',
    'port' => 'Prievadas',
    'port_help' => 'Prievadas, kuris yra naudojamas šiame „MySQL“ serveryje.',
    'max_database' => 'Didžiausias duomenų bazių kiekis',
    'max_databases_help' => 'Didžiausias duomenų bazių kiekis, kurias galima sukurti šiame serveryje. Jei ši riba pasiekta, šiame serveryje negalės kurti naujų duomenų bazių. Tuščia reikšmė yra neribota.',
    'display_name' => 'Rodomas pavadinimas',
    'display_name_help' => 'Trumpas identifikatorius, naudojamas šiam serveriui atskirti nuo kitų. Turi būti nuo 1 iki 60 simbolių, pavyzdžiui, us.nyc.lvl3.',
    'username' => 'Vartotojo vardas',
    'username_help' => 'Paskyros, turinčios pakankamai teisių kurti naujus naudotojus ir duomenų bazes sistemoje, vartotojo vardas.',
    'password' => 'Slaptažodis',
    'password_help' => 'Duomenų bazės naudotojo slaptažodis.',
    'linked_nodes' => 'Prijungti „node“',
    'linked_nodes_help' => 'Šis nustatymas pagal numatytuosius nustatymus taikomas tik šiam duomenų bazės prievadui, kai duomenų bazė pridedama prie pasirinkto „node“ serverio.',
    'connection_error' => 'Klaida jungiantis prie duomenų bazės serverio',
    'no_database_hosts' => 'Duomenų bazės serverių nėra',
    'no_nodes' => 'Nėra nei vieno „node“',
    'delete_help' => 'Duomenų bazės serveris turi duomenų bazes',
    'unlimited' => 'Neribota',
    'anywhere' => 'Bet kur',

    'rotate' => 'Pasukti',
    'rotate_password' => 'Pakeisti slaptažodį',
    'rotated' => 'Slaptažodis pakeistas',
    'rotate_error' => 'Slaptažodį pakeisti nepavyko',
    'databases' => 'Duomenų bazės',

    'setup' => [
        'preparations' => 'Paruošimai',
        'database_setup' => 'Duomenų bazės pradinė konfiguracija',
        'panel_setup' => 'Skydelio Sąranka',

        'note' => 'Šiuo laiku, tik MySQL/MariaDB duomenų bazės yra palaikomos.',
        'different_server' => 'Ar skydelis ir duomenų bazė <i>nėra</i> tame pačiame serveryje?',

        'database_user' => 'Duomenų bazės vartotojas',
        'cli_login' => 'Naudokite kodą <code>mysql -u root -p</code> jei norite prieiti mysql cli.',
        'command_create_user' => 'Komanda sukurti naudotoją.',
        'command_assign_permissions' => 'Komanda priskirti pareigas.',
        'cli_exit' => 'Kad išeitumete iš mysql cli naudokite kodą <code>exit</code>.',
        'external_access' => 'Išorinė prieiga.',
        'allow_external_access' => '
<p>Tikėtina, kad turėsite leisti išorinę prieigą prie šios MySQL instancijos, kad serveriai galėtų prie jos prisijungti.</p>
<br>
<p>Norėdami tai padaryti, atidarykite failą <code>my.cnf</code>, kurio vieta priklauso nuo jūsų OS ir MySQL diegimo būdo. Galite įvesti find <code>/etc -iname my.cnf</code>, kad jį rastumėte.</p>
<br>
<p>Atidarykite failą <code>my.cnf</code>, pridėkite toliau pateiktą tekstą failo apačioje ir išsaugokite jį:<br>
<code>[mysqld]<br>bind-address=0.0.0.0</code></p>
<br>
<p>Paleiskite MySQL / MariaDB iš naujo, kad pritaikytumėte šiuos pakeitimus. Tai pakeis numatytąją MySQL konfigūraciją, kuri pagal numatytuosius nustatymus priims užklausas tik iš localhost. Atnaujinus šią konfigūraciją, bus leidžiami ryšiai visose sąsajose, taigi, ir išoriniai ryšiai. Įsitikinkite, kad užkardoje leidžiamas MySQL prievadas (numatytasis 3306).</p>                                ',
    ],
];
