<?php

return [
    'greeting' => 'Olá :name!',

    'account_created' => [
        'body' => 'Você recebeu este correio eletrônico porque foi criado uma conta para si em :app.',
        'username' => 'Nome de usuário: :username',
        'email' => 'Endereço eletrônico: :email',
        'action' => 'Configurar sua conta',
    ],

    'added_to_server' => [
        'body' => 'Você foi adicionado como sub-usuário para o seguinte servidor, permitindo-lhe um certo controle sobre o servidor.',
        'server_name' => 'Nome do servidor: :name',
        'action' => 'Visitar Servidor',
    ],

    'removed_from_server' => [
        'body' => 'Você foi removido como sub-usuário para o seguinte servidor.',
        'server_name' => 'Nome do servidor: :name',
        'action' => 'Visitar Painel',
    ],

    'server_installed' => [
        'body' => 'Seu servidor terminou a instalação e agora está pronto para você usar.',
        'server_name' => 'Nome do servidor: :name',
        'action' => 'Faça login e comece a usar',
    ],

    'backup_completed' => [
        'body_success' => 'O backup foi criado com sucesso.',
        'body_failed' => 'Criação de Backup Falhou.',
        'backup_name' => 'Nome do backup: :name',
        'server_name' => 'Nome do servidor: :name',
        'action' => 'Ver backups',
    ],

    'mail_tested' => [
        'subject' => 'Teste de mensagem do painel',
        'body' => 'Este é um teste do sistema de correio eletrônico do painel, Você está pronto para ir!',
    ],
];
