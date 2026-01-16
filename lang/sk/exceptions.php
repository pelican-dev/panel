<?php

return [
    'daemon_connection_failed' => 'Pri pokuse o komunikáciu s daemonom sa vyskytla chyba s kódom HTTP/:code. Táto chyba bola zaznamenaná.',
    'node' => [
        'servers_attached' => 'Uzol nemôže mať priradené žiadne servery aby mohol byť vymazaný.',
        'error_connecting' => 'Chyba pri pripojení k :node',
        'daemon_off_config_updated' => 'Konfigurácia daemonu <strong>bola aktualizovaná</strong>, no pri pokuse o automatickú aktualizáciu konfigurácie na daemonovi sa vyskytla chyba. Budete musieť manuálne aktualizovať konfiguračný súbor (config.yml) aby sa táto zmena aplikovala na daemon.',
    ],
    'allocations' => [
        'server_using' => 'Server je momentálne priradený k tejto alokácii. Alokácia môže byť zmazaná, len ak k nej nieje priradený žiadny server.',
        'too_many_ports' => 'Pridanie viac ako 1000 portov v jednom rozsahu nieje podporované.',
        'invalid_mapping' => 'Mapovanie poskytnuté pre port :port nieje správne a nemohlo byť spracované.',
        'cidr_out_of_range' => 'CIDR notácia dovoľuje len masky medzi /25 a /32.',
        'port_out_of_range' => 'Porty v alokácii musia mať vyššiu hodnotu ako 1024 a menšiu, alebo rovnú 65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'Vajce s priradenými aktívnymi servermi nemože byť vymazané z panelu.',
        'invalid_copy_id' => 'Vybrané vajce na kopírovanie skriptu buď neexistuje, alebo samé ešte skript kopíruje.',
        'has_children' => 'Toto vajce je rodičom ďalšieho jedného, alebo viacero iných vajec. Prosím zmažte tieto vajcia pred zmazaním tohto vajca.',
    ],
    'variables' => [
        'env_not_unique' => 'Premenná prostredia :name musí byť unikátna tomuto vajcu.',
        'reserved_name' => 'Premenná prostredia :name je chránená a nemôže byť priradená premennej.',
        'bad_validation_rule' => 'Pravidlo validácie ":rule" nieje validné pravidlo pre túto aplikáciu.',
    ],
    'importer' => [
        'json_error' => 'Pri pokuse o analýzu JSON súboru sa vyskytla chyba: :error.',
        'file_error' => 'Poskytnutý JSON súbor nieje validný.',
        'invalid_json_provided' => 'JSON súbor nieje vo formáte, ktorý je možné rozpoznať.',
    ],
    'subusers' => [
        'editing_self' => 'Upravovať vlastného podpoužívateľa nieje povolené.',
        'user_is_owner' => 'Nemôžete pridať majiteľa serveru ako podpoužívateľa pre tento server.',
        'subuser_exists' => 'Používateľov s rovnakou emailovou adresou je už priradený ako podpoužívateľ pre tento server.',
    ],
    'databases' => [
        'delete_has_databases' => 'Nieje možné odstrániť databázový server, ktorý má priradené aktívne databázy.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'Maximálny časový interval pre reťazovú úlohu je 15 minút.',
    ],
    'locations' => [
        'has_nodes' => 'Nieje možné zmazať lokáciu, ktorá má priradené aktívne uzly.',
    ],
    'users' => [
        'is_self' => 'Nie je možné odstrániť svoj vlastný používateľský účet.',
        'has_servers' => 'Nie je možné odstrániť používateľa s aktívnymi servermi pripojenými k ich účtu. Pred pokračovaním odstráňte ich servery.',
        'node_revocation_failed' => 'Nebolo možné odobrať kľúče na <a href=":link"> Uzol #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Neboli nájdené žiadne uzly spĺňajúce požiadavky pre automatické nasadenie.',
        'no_viable_allocations' => 'Neboli nájdené žiadne alokácie spĺňajúce požiadavky pre automatické nasadenie.',
    ],
    'api' => [
        'resource_not_found' => 'Požadovaný zdroj neexistuje na tomto servery.',
    ],
    'mount' => [
        'servers_attached' => 'Úložisko nemôže byť priradené k žiadnym serverom aby mohlo byť zmazané.',
    ],
    'server' => [
        'marked_as_failed' => 'Tento server ešte nedokončil svoj proces inštalácie, prosím, skúste to znovu.',
    ],
];
