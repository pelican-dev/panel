<?php

return [
    'greeting' => 'Hello :name!',

    'account_created' => [
        'body' => 'Azért kapta ezt az e-mailt, mert fiókot hoztak létre az Ön számára a(z) :app felületén.',
        'username' => 'Felhasználónév: :username',
        'email' => 'Email: :email',
        'action' => 'Állítsd be fiokod',
    ],

    'added_to_server' => [
        'body' => 'Ön alszintű felhasználói hozzáférést kapott az alábbi szerverhez, ami korlátozott irányítást tesz lehetővé számodra.',
        'server_name' => 'Szerver név: :name',
        'action' => 'Szerver meglátogatása',
    ],

    'removed_from_server' => [
        'body' => 'Önt eltávolították az alábbi szerver alfelhasználói közül.',
        'server_name' => 'Szerver név: :name',
        'action' => 'Panel meglátogatása',
    ],

    'server_installed' => [
        'body' => 'Az ön szervere beállítása befejeződött, mostmár készen áll a használatra.',
        'server_name' => 'Szerver név: :name',
        'action' => 'Jelentkezzen be a használat megkezdéséhez',
    ],

    'backup_completed' => [
        'body_success' => 'A biztonsági másolat létrehozása sikeresen befejeződött.',
        'body_failed' => 'A biztonsági másolat létrehozása sikertelen volt.',
        'backup_name' => 'Biztonsági Mentés Neve: :name',
        'server_name' => 'Szerver Neve: :name',
        'action' => 'Biztonsági Mentések Megtekintése',
    ],

    'mail_tested' => [
        'subject' => 'Panel próba üzenet',
        'body' => 'Ez a Panel levelezőrendszerének tesztje. Minden rendben!',
    ],
];
