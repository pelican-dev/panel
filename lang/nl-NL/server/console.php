<?php

return [
    'title' => 'Console',
    'command' => 'Typ een commando...',
    'command_blocked' => 'Server Offline...',
    'command_blocked_title' => 'Kan geen commando verzenden als de server offline is',
    'open_in_admin' => 'Open als Beheerder',
    'power_actions' => [
        'start' => 'Start',
        'stop' => 'Stop',
        'restart' => 'Herstarten',
        'kill' => 'Geforceerd stoppen',
        'kill_tooltip' => 'Dit kan leiden tot datacorruptie en/of gegevensverlies!',
    ],
    'labels' => [
        'cpu' => 'CPU',
        'memory' => 'Geheugen',
        'network' => 'Netwerk',
        'disk' => 'Schijf',
        'name' => 'Naam',
        'status' => 'Status',
        'address' => 'IP-adres',
        'unavailable' => 'Niet beschikbaar',
    ],
    'status' => [
        'created' => 'Aangemaakt',
        'starting' => 'Bezig met opstarten',
        'running' => 'Actief',
        'restarting' => 'Bezig met herstarten',
        'exited' => 'Verlaten',
        'paused' => 'Gepauzeerd',
        'dead' => 'Dood',
        'removing' => 'Bezig met verwijderen',
        'stopping' => 'Bezig met Stoppen',
        'offline' => 'Offline',
        'missing' => 'Ontbrekend',
    ],
    'websocket_error' => [
        'title' => 'Kon geen verbinding maken met de websocket!',
        'body' => 'Bekijk de browser console voor meer details.',
    ],
];
