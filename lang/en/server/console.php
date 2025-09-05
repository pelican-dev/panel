<?php

return [
    'title' => 'Console',
    'command' => 'Type a command...',
    'command_blocked' => 'Server Offline...',
    'command_blocked_title' => 'Can\'t send command when the server is Offline',
    'open_in_admin' => 'Open in Admin',
    'power_actions' => [
        'start' => 'Start',
        'stop' => 'Stop',
        'restart' => 'Restart',
        'kill' => 'Kill',
        'kill_tooltip' => 'This can result in data corruption and/or data loss!',
    ],
    'labels' => [
        'cpu' => 'CPU',
        'memory' => 'Memory',
        'network' => 'Network',
        'disk' => 'Disk',
        'name' => 'Name',
        'status' => 'Status',
        'address' => 'Address',
        'unavailable' => 'Unavailable',
    ],
    'status' => [
        'created' => 'Created',
        'starting' => 'Starting',
        'running' => 'Running',
        'restarting' => 'Restarting',
        'exited' => 'Exited',
        'paused' => 'Paused',
        'dead' => 'Dead',
        'removing' => 'Removing',
        'stopping' => 'Stopping',
        'offline' => 'Offline',
        'missing' => 'Missing',
    ],
    'websocket_error' => [
        'title' => 'Could not connect to websocket!',
        'body' => 'Check your browser console for more details.',
    ],
];
