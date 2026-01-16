<?php

return [
    'title' => 'Instalador do painel',
    'requirements' => [
        'title' => 'Requisitos do servidor',
        'sections' => [
            'version' => [
                'title' => 'Versão do PHP',
                'or_newer' => ':version ou mais recente',
                'content' => 'Sua versão do PHP é :version.',
            ],
            'extensions' => [
                'title' => 'Extensões PHP',
                'good' => 'Todas as extensões PHP necessárias estão instaladas.',
                'bad' => 'As seguintes extensões do PHP estão faltando: :extensions',
            ],
            'permissions' => [
                'title' => 'Permissões da pasta',
                'good' => 'Todas as pastas têm as permissões corretas.',
                'bad' => 'As seguintes pastas têm permissões incorretas: :folders',
            ],
        ],
        'exception' => 'Alguns requisitos estão faltando',
    ],
    'environment' => [
        'title' => 'Ambiente',
        'fields' => [
            'app_name' => 'Nome do Aplicativo',
            'app_name_help' => 'Este será o nome do seu painel.',
            'app_url' => 'URL do aplicativo',
            'app_url_help' => 'Esta será a URL de acesso ao seu painel.',
            'account' => [
                'section' => 'Usuário administrador',
                'email' => 'E-mail',
                'username' => 'Nome de usuário',
                'password' => 'Senha',
            ],
        ],
    ],
    'database' => [
        'title' => 'Banco de dados',
        'driver' => 'Driver do banco de dados',
        'driver_help' => 'O driver utilizado para o banco de dados do painel. Recomendamos "SQLite".',
        'fields' => [
            'host' => 'Host do banco de dados',
            'host_help' => 'Host do banco de dados. Certifique-se de que está acessível.',
            'port' => 'Porta do banco de dados',
            'port_help' => 'A porta do seu banco de dados.',
            'path' => 'Caminho do banco de dados',
            'path_help' => 'O caminho do seu arquivo .sqlite relativo à pasta do banco de dados.',
            'name' => 'Nome do banco de dados',
            'name_help' => 'O nome do banco de dados do painel.',
            'username' => 'Usuário do banco de dados',
            'username_help' => 'O username do seu banco de dados.',
            'password' => 'Senha do banco de dados',
            'password_help' => 'A senha do usuário do banco de dados. Pode estar vazio.',
        ],
        'exceptions' => [
            'connection' => 'Falha na conexão com o banco de dados',
            'migration' => 'Falha na migrações',
        ],
    ],
    'egg' => [
        'title' => 'Eggs',
        'no_eggs' => 'Sem eggs disponíveis',
        'background_install_started' => 'Instalação de egg iniciada',
        'background_install_description' => 'A instalação de :count eggs foi colocada na fila e continuará em segundo plano.',
        'exceptions' => [
            'failed_to_update' => 'Falha ao atualizar index de eggs',
            'no_eggs' => 'Não há eggs disponíveis para instalação neste momento.',
            'installation_failed' => 'Falha ao instalar eggs selecionados. Por favor, importe-os após a instalação ultilizando a lista de eggs.',
        ],
    ],
    'session' => [
        'title' => 'Sessão',
        'driver' => 'Drivers de sessão',
        'driver_help' => 'O driver usado para armazenar as sessões. Recomendamos "Filesystem" ou "Database".',
    ],
    'cache' => [
        'title' => 'Cache',
        'driver' => 'Driver de cache',
        'driver_help' => 'O driver usado para cache. Recomendamos o "Filesystem".',
        'fields' => [
            'host' => 'Host do Redis',
            'host_help' => 'O host do seu servidor redis. Certifique-se de que esteja acessível.',
            'port' => 'Porta do Redis',
            'port_help' => 'A porta do seu servidor redis.',
            'username' => 'Usuário do Redis',
            'username_help' => 'O username do seu servidor Redis. Pode ser vazio',
            'password' => 'Senha do Redis',
            'password_help' => 'A senha do usuário Redis. Pode estar vazia.',
        ],
        'exception' => 'Falha na conexão com o Redis',
    ],
    'queue' => [
        'title' => 'Fila',
        'driver' => 'Driver da fila',
        'driver_help' => 'O driver usado para lidar com filas. Recomendamos "Database".',
        'fields' => [
            'done' => 'Eu fiz os dois passos abaixo.',
            'done_validation' => 'Você precisa fazer os dois passos antes de continuar!',
            'crontab' => 'Execute o comando a seguir para configurar seu crontab. Note que <code>www-data</code> é o usuário do seu servidor web. Em alguns sistemas, este nome de usuário pode ser diferente!',
            'service' => 'Para configurar a fila de serviço do worker você simplesmente tem que executar o seguinte comando.',
        ],
    ],
    'exceptions' => [
        'write_env' => 'Não foi possível escrever no arquivo .env',
        'migration' => 'Não foi possível executar as migrações',
        'create_user' => 'Não foi possível criar o usuário administrador',
    ],
    'next_step' => 'Próximo passo',
    'finish' => 'Finalizar',
];
