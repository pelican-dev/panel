<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => 'Nurodykite el. pašto adresą, iš kurio turėtų būti siunčiami „eggs“ iš valdymo punkto. Tai turi būti galiojantis el. pašto adresas.',
            'url' => 'Programos nuoroda PRIVALO prasidėti su https:// arba http://, priklausomai nuo to, ar naudojate „SSL“, ar ne. Jei nenurodysite schemos, jūsų el. laiškai ir kitas turinys nukreips į neteisingą vietą.',
            'timezone' => 'Laiko zona turėtų atitikti vieną iš „PHP“ palaikomų laiko zonų. Jei nesate tikri, peržiūrėkite https://php.net/manual/en/timezones.php.',
        ],
        'redis' => [
            'note' => 'Pasirinkote „Redis“ tvarkyklę vienai ar kelioms parinktim, pateikite tinkamą prisijungimo informaciją žemiau. Daugeliu atvejų galite naudoti numatytas reikšmes, nebent pakeitėte savo sąranką.',
            'comment' => 'Pagal nutylėjimą „Redis“ serverio instancijoje vartotojo vardas yra „default“ ir nėra slaptažodžio, nes jis veikia lokaliai ir yra neprieinamas išorės pasauliui. Jei taip yra, tiesiog paspauskite Enter, neįvedę jokios reikšmės.',
            'confirm' => 'Atrodo, kad laukas :field jau apibrėžtas „Redis“, ar norėtumėte jį pakeisti?',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => 'Geriausia nenaudoti „localhost“ kaip jūsų duomenų bazės šeimininko, nes pastebėta dažnų lizdo prisijungimo problemų. Jei norite naudoti vietinį prisijungimą, turėtumėte naudoti „127.0.0.1“.',
        'DB_USERNAME_note' => 'Naudoti „root“ paskyrą „MySQL“ prisijungimams ne tik yra labai nepageidaujama, bet ir šiai programai draudžiama. Turėsite sukurti „MySQL“ vartotoją šiai programai.',
        'DB_PASSWORD_note' => 'Atrodo, kad jau nustatytas „MySQL“ prisijungimo slaptažodis, ar norėtumėte jį pakeisti?',
        'DB_error_2' => 'Jūsų prisijungimo duomenys NEBUVO išsaugoti. Prieš tęsdami turėsite pateikti tinkamą prisijungimo informaciją.',
        'go_back' => 'Grįžkite atgal ir bandykite dar kartą',
    ],
    'make_node' => [
        'name' => 'Įveskite trumpą identifikatorių, skirtą atskirti šį „node“ nuo kitų',
        'description' => 'Įveskite aprašymą, skirtą identifikuoti šį „node“',
        'scheme' => 'Prašome įvesti https, jei naudojate „SSL“, arba http, jei nesinaudojate „SSL“',
        'fqdn' => 'Įveskite domeno vardą (pvz., node.example.com), kuris bus naudojamas prisijungimui prie „daemon“. IP adresą galima naudoti tik jei nesinaudojate „SSL“ šiam „node“',
        'public' => 'Ar šis „node“ turėtų būti viešas? Pastaba: nustatant mazgą kaip privatų, prarasite galimybę automatiškai diegti į šį mazgą.',
        'behind_proxy' => 'Ar jūsų „FQDN“ yra už tarpinio serverio?',
        'maintenance_mode' => 'Ar turi būti įjungtas techninio aptarnavimo režimas?',
        'memory' => 'Įveskite didžiausią atminties kiekį',
        'memory_overallocate' => 'Įveskite, kiek atminties perviršiai skiriama, -1 išjungs patikrinimą, o 0 neleis kurti naujų serverių',
        'disk' => 'Įveskite didžiausią disko vietos kiekį',
        'disk_overallocate' => 'Įveskite, kiek disko vietos perviršiai skiriama, -1 išjungs patikrinimą, o 0 neleis kurti naujo serverio',
        'cpu' => 'Įveskite didžiausią „CPU“ kiekį',
        'cpu_overallocate' => 'Įveskite, kiek „CPU“ perviršiai skiriama, -1 išjungs patikrinimą, o 0 neleis kurti naujo serverio',
        'upload_size' => 'Įveskite didžiausią failo įkėlimo dydį',
        'daemonListen' => 'Įveskite „daemon“ klausymo prievadą',
        'daemonSFTP' => 'Įveskite „daemon“ „SFTP“ klausymo prievadą',
        'daemonSFTPAlias' => 'Įveskite „daemon“ „SFTP“ pseudonimą (gali būti tuščias)',
        'daemonBase' => 'Įveskite pagrindinį katalogą',
        'success' => 'Naujas „node“ sėkmingai sukurtas, pavadinimu :name ir su Id :id',
    ],
    'node_config' => [
        'error_not_exist' => 'Pasirinktas „node“ neegzistuoja.',
        'error_invalid_format' => 'Nurodytas neteisingas formatas. Galimos reikšmės yra „yaml“ ir „json“.',
    ],
    'key_generate' => [
        'error_already_exist' => 'Atrodo, kad jau sukonfigūruotas programos šifravimo raktas. Tęsdami šį procesą perrašysite tą raktą ir gali kilti esamų užšifruotų duomenų sugadinimas. NETĘSKITE, JEI NEŽINOTE, KĄ DARYTI.',
        'understand' => 'Suprantu šio komandos vykdymo pasekmes ir prisiimu visą atsakomybę už užšifruotų duomenų praradimą.',
        'continue' => 'Ar tikrai norite tęsti? Programos šifravimo rakto keitimas SUKELS DUOMENŲ PRARADIMĄ.',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => 'Nėra suplanuotų užduočių serveriams, kurias reikia vykdyti.',
            'error_message' => 'Apdorojant tvarkaraštį įvyko klaida: ',
        ],
    ],
    'upgrade' => [
        'integrity' => 'Ši komanda nepatikrina atsisiųstų resursų vientisumo. Prašome įsitikinti, kad pasitikite atsisiuntimo šaltiniu, prieš tęsdami. Jei nenorite atsisiųsti archyvo, nurodykite tai naudodami --skip-download parinktį, arba atsakydami „no“ į žemiau pateiktą klausimą.',
        'source_url' => 'Atsisiuntimo šaltinis (nustatoma su --url=):',
        'php_version' => 'Negalima vykdyti savi atnaujinimo proceso. Mažiausia reikalaujama „PHP“ versija yra 7.4.0, o jūsų turima',
        'skipDownload' => 'Ar norėtumėte atsisiųsti ir išpakuoti archyvo failus naujausiai versijai?',
        'webserver_user' => 'Jūsų „webserver“ vartotojas aptiktas kaip <fg=blue>[{:user}]:</>, ar tai teisinga?',
        'name_webserver' => 'Prašome įvesti vartotojo, paleidžiančio „webserver“ procesą, vardą. Tai skiriasi priklausomai nuo sistemos, bet paprastai yra „www-data“, „nginx“ arba „apache“.',
        'group_webserver' => 'Jūsų „webserver“ grupė aptikta kaip <fg=blue>[{:group}]:</>, ar tai teisinga?',
        'group_webserver_question' => 'Prašome įvesti grupės, paleidžiančios „webserver“ procesą, pavadinimą. Paprastai ji sutampa su jūsų vartotoju.',
        'are_your_sure' => 'Ar esate tikri, kad norite vykdyti atnaujinimo procesą savo valdymo punkto?',
        'terminated' => 'Atnaujinimo procesas nutrauktas vartotojo.',
        'success' => 'Valdymo punktas sėkmingai atnaujintas. Prašome įsitikinti, kad taip pat atnaujinsite bet kurias „daemon“ instancijas',

    ],
];
