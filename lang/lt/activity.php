<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Nepavyko prisijungti',
        'success' => 'Prisijungta',
        'password-reset' => 'Slaptažodžio atkūrimas',
        'checkpoint' => 'Prašyta Dviejų faktorių autentifikacija',
        'recovery-token' => 'Naudota dviejų faktorių atgavimo tokeną',
        'token' => 'Išspręstas dviejų faktorių autentifikacijos patikrinimas',
        'ip-blocked' => 'Užblokuota užklausa iš neįtraukto į sąrašą IP adreso identifikatoriaus: <b>:identifier</b>',
        'sftp' => [
            'fail' => 'Nepavyko prisijungti prie SFTP',
        ],
    ],
    'user' => [
        'account' => [
            'username-changed' => 'Vartotojo vardas pakeistas iš <b>:old</b> į <b>:new</b>',
            'email-changed' => 'El. paštas pakeistas iš <b>:old</b> į <b>:new</b>',
            'password-changed' => 'Slaptažodis pakeistas',
        ],
        'api-key' => [
            'create' => 'Sukurtas naujas API raktas <b>:identifier</b>',
            'delete' => 'Ištrintas API raktas <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => 'Prie paskyros pridėtas SSH raktas <b>:fingerprint</b>',
            'delete' => 'Iš paskyros pašalintas SSH raktas <b>:fingerprint</b>',
        ],
        'two-factor' => [
            'create' => 'Įjungta dviejų faktorių autentifikacija',
            'delete' => 'Išjungta dviejų faktorių autentifikacija',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'Serveryje įvykdyta komanda "<b>:command</b>"',
        ],
        'power' => [
            'start' => 'Serveris paleistas',
            'stop' => 'Serveris sustabdytas',
            'restart' => 'Serveris perkrautas',
            'kill' => 'Nužudė serverio procesą',
        ],
        'backup' => [
            'download' => 'Atsisiųstas <b>:name</b> atsarginės kopijos failas',
            'delete' => 'Ištrinta <b>:name</b> atsarginė kopija',
            'restore' => 'Atstatyta <b>:name</b> atsarginė kopija (ištrinti failai: <b>:truncate</b>)',
            'restore-complete' => 'Sėkmingai užbaigtas <b>:name</b> atsarginės kopijos atkūrimas',
            'restore-failed' => 'Nepavyko atkurti <b>:name</b> atsarginės kopijos',
            'start' => 'Pradėta nauja <b>:name</b> atsarginė kopija',
            'complete' => 'Pažymėta <b>:name</b> atsarginė kopija kaip užbaigta',
            'fail' => 'Pažymėta <b>:name</b> atsarginė kopija kaip nepavykus',
            'lock' => 'Užrakinta <b>:name</b> atsarginė kopija',
            'unlock' => 'Atrakinta <b>:name</b> atsarginė kopija',
            'rename' => 'Atsarginės kopijos pavadinimas pakeistas iš „<b>:old_name</b>“ į „<b>:new_name</b>“',
        ],
        'database' => [
            'create' => 'Sukurta nauja duomenų bazė <b>:name</b>',
            'rotate-password' => 'Atnaujintas slaptažodis duomenų bazei <b>:name</b>',
            'delete' => 'Ištrinta duomenų bazė <b>:name</b>',
        ],
        'file' => [
            'compress' => 'Suspausta <b>:directory:files</b>|Suspausta <b>:count</b> failų kataloge <b>:directory</b>',
            'read' => 'Peržiūrėtas <b>:file</b> turinys',
            'copy' => 'Nukopijuotas <b>:file</b>',
            'create-directory' => 'Sukurta katalogas <b>:directory:name</b>',
            'decompress' => 'Išpakuotas <b>:file</b> kataloge <b>:directory</b>',
            'delete' => 'Ištrinta <b>:directory:files</b>|Ištrinta <b>:count</b> failų kataloge <b>:directory</b>',
            'download' => 'Atsisiųstas <b>:file</b>',
            'pull' => 'Atsisiuntė nuotolinį failą iš <b>:url</b> į <b>:directory</b>',
            'rename' => 'Perkelta/Pervadinta iš <b>:from</b> į <b>:to</b>| Perkelta/Pervadinta <b>:count</b> failu į <b>:directory</b>',
            'write' => 'Įrašytas naujas turinys į <b>:file</b>',
            'upload' => 'Pradėjo failo įkėlimą',
            'uploaded' => 'Įkeltas <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => 'Užblokavo SFTP prieeigą, dėl teisių',
            'create' => 'Sukurta <b>:files</b>|Sukurta <b>:count</b> naujų failų',
            'write' => 'Pakeistas <b>:files</b> turinys|Pakeista <b>:count</b> failų turinys',
            'delete' => 'Ištrinta <b>:files</b>|Ištrinta <b>:count</b> failų',
            'create-directory' => 'Sukurta <b>:files</b> direktorija|Sukurta <b>:count</b> direktorijų',
            'rename' => 'Pervadinta <b>:from</b> į <b>:to</b>|Pervadinta arba perkelta <b>:count</b> failų',
        ],
        'allocation' => [
            'create' => 'Pridėta <b>:allocation</b> prie serverio',
            'notes' => 'Atnaujinti užrašai prie <b>:allocation</b> nuo "<b>:old</b>" iki "<b>:new</b>"',
            'primary' => 'Nustatyta <b>:allocation</b> kaip pagrindinis serverio paskirstymas',
            'delete' => 'Ištrintas <b>:allocation</b> paskirstymas',
        ],
        'schedule' => [
            'create' => 'Sukurta <b>:name</b> tvarka',
            'update' => 'Atnaujinta <b>:name</b> tvarka',
            'execute' => 'Rankiniu būdu paleista <b>:name</b> tvarka',
            'delete' => 'Ištrinta <b>:name</b> tvarka',
        ],
        'task' => [
            'create' => 'Sukurta nauja užduotis "<b>:action</b>" <b>:name</b> tvarkai',
            'update' => 'Atnaujinta užduotis "<b>:action</b>" <b>:name</b> tvarkai',
            'delete' => 'Ištrinta "<b>:action</b>" užduotis <b>:name</b> grafikui',
        ],
        'settings' => [
            'rename' => 'Pervadintas serveris iš "<b>:old</b>" į "<b>:new</b>"',
            'description' => 'Pakeistas serverio aprašymas iš "<b>:old</b>" į "<b>:new</b>"',
            'reinstall' => 'Perinstaliuotas serveris',
        ],
        'startup' => [
            'edit' => 'Pakeista <b>:variable</b> reikšmė iš "<b>:old</b>" į "<b>:new</b>"',
            'image' => 'Atnaujintas „Docker“ atvaizdas serveriui iš <b>:old</b> į <b>:new</b>',
            'command' => 'Serverio paleisties komanda buvo atnaujinta iš <b>:old</b> į <b>:new</b>',
        ],
        'subuser' => [
            'create' => 'Pridėtas <b>:email</b> kaip papildomas vartotojas',
            'update' => 'Atnaujintos papildomo vartotojo teisės <b>:email</b>',
            'delete' => 'Pašalintas <b>:email</b> kaip papildomas vartotojas',
        ],
        'crashed' => 'Serveris sugriuvo',
    ],
];
