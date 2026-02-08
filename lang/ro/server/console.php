<?php

return [
    'title' => 'Consolă',
    'command' => 'Scrie o comandă...',
    'command_blocked' => 'Server offline...',
    'command_blocked_title' => 'Nu se poate trimite comanda atunci când serverul este offline',
    'open_in_admin' => 'Deschide în Admin',
    'power_actions' => [
        'start' => 'Pornește',
        'stop' => 'Oprește',
        'restart' => 'Repornește',
        'kill' => 'Oprește forțat',
        'kill_tooltip' => 'Acest lucru poate duce la corupție și/sau pierdere de date!',
    ],
    'labels' => [
        'cpu' => 'Procesor',
        'memory' => 'Memorie',
        'network' => 'Rețea',
        'disk' => 'Stocare',
        'name' => 'Nume',
        'status' => 'Status',
        'address' => 'Adresă',
        'unavailable' => 'Indisponibil',
    ],
    'status' => [
        'created' => 'Creat',
        'starting' => 'În pornire',
        'running' => 'În rulare',
        'restarting' => 'În repornire',
        'exited' => 'Ieșit',
        'paused' => 'Pauză',
        'dead' => 'Inactiv',
        'removing' => 'Se elimină',
        'stopping' => 'Oprire',
        'offline' => 'Offline',
        'missing' => 'Lipsă',
    ],
    'websocket_error' => [
        'title' => 'Nu s-a putut conecta la websocket!',
        'body' => 'Verifică consola browser-ului pentru mai multe detalii.',
    ],
];
