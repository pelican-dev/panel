<?php

return [
    'title' => 'Console',
    'command' => 'Digite um comando...',
    'command_blocked' => 'Servidor Offline...',
    'command_blocked_title' => 'Não é possível enviar um comando quando o servidor está Offline',
    'open_in_admin' => 'Abrir como Administrador',
    'power_actions' => [
        'start' => 'Iniciar',
        'stop' => 'Parar',
        'restart' => 'Reiniciar',
        'kill' => 'Forçar Parada',
        'kill_tooltip' => 'Isso pode resultar em corrupção e/ou perda de dados!',
    ],
    'labels' => [
        'cpu' => 'CPU',
        'memory' => 'Memória',
        'network' => 'Rede',
        'disk' => 'Disco',
        'name' => 'Nome',
        'status' => 'Status',
        'address' => 'Endereço',
        'unavailable' => 'Indisponível',
    ],
    'status' => [
        'created' => 'Criado',
        'starting' => 'Iniciando',
        'running' => 'Em execução',
        'restarting' => 'Reiniciando',
        'exited' => 'Encerrado',
        'paused' => 'Pausado',
        'dead' => 'Encerrado Forçadamente',
        'removing' => 'Excluindo',
        'stopping' => 'Parando',
        'offline' => 'Offline',
        'missing' => 'Ausente',
    ],
    'websocket_error' => [
        'title' => 'Não foi possível conectar ao websocket!',
        'body' => 'Verifique o console do seu navegador para mais detalhes.',
    ],
];
