<?php

return [
    'nav_title' => 'Servidores de Banco de Dados',
    'model_label' => 'Servidor de Banco de Dados',
    'model_label_plural' => 'Servidores Banco de Dados',
    'table' => [
        'database' => 'Banco de Dados',
        'name' => 'Nome',
        'host' => 'Servidor',
        'port' => 'Porta',
        'name_helper' => 'Deixar essa opção em branco irá gerar um nome aleatório.',
        'username' => 'Nome de Usuário',
        'password' => 'Senha',
        'remote' => 'Conexões de',
        'remote_helper' => 'De onde devem ser permitidas as conexões. Deixar em branco para permitir conexões a partir de qualquer local.',
        'max_connections' => 'Conexões máximas',
        'created_at' => 'Criado em',
        'connection_string' => 'Ligação de conexão JDBC',
    ],
    'error' => 'Erro ao ligar ao servidor',
    'host' => 'Servidor',
    'host_help' => 'O endereço IP ou o nome de domínio que deve ser utilizado quando se tenta ligar a este servidor MySQL a partir deste Painel para criar novas bases de dados.',
    'port' => 'Porta',
    'port_help' => 'A porta em que o MySQL está a ser executado para este servidor.',
    'max_database' => 'Máximo de bases de dados',
    'max_databases_help' => 'O número máximo de bases de dados que podem ser criadas neste servidor. Se o limite for atingido, não podem ser criadas novas bases de dados neste servidor. Em branco é ilimitado.',
    'display_name' => 'Nome de exibição',
    'display_name_help' => 'Um identificador curto utilizado para distinguir esta localização de outras. Deve ter entre 1 e 60 caracteres, por exemplo, pt.lx.01.',
    'username' => 'Nome de utilizador',
    'username_help' => 'O nome de utilizador de uma conta que tem permissões suficientes para criar novos utilizadores e bases de dados no sistema.',
    'password' => 'Senha',
    'password_help' => 'A palavra-passe do utilizador da base de dados.',
    'linked_nodes' => 'Nós lincado',
    'linked_nodes_help' => 'Esta definição só é predefinida para este servidor de base de dados quando se adiciona uma base de dados a um servidor no Node selecionado.',
    'connection_error' => 'Erro ao ligar ao servidor da base de dados',
    'no_database_hosts' => 'Sem servidores de bases de dados',
    'no_nodes' => 'Sem Nós',
    'delete_help' => 'O servidor da base de dados tem bases de dados',
    'unlimited' => 'Sem limite',
    'anywhere' => 'Qualquer lugar',

    'rotate' => 'Alterar',
    'rotate_password' => 'Alterar palavra-passe',
    'rotated' => 'Palavra-passe alterada',
    'rotate_error' => 'Falha na alteração da palavra-passe',
    'databases' => 'Bases de Dados',

    'setup' => [
        'preparations' => 'Preparações',
        'database_setup' => 'Configurações do Banco de Dados',
        'panel_setup' => 'Configuração do Painel',

        'note' => 'No momento, apenas bancos de dados MySQL/ MariaDB são suportados!',
        'different_server' => 'O painel e o banco de dados <i>não</i> estão no mesmo servidor?',

        'database_user' => 'Utilizador do Banco de Dados',
        'cli_login' => 'Use <code>mysql -u root -p</code> para acessar o MySQL CLI.',
        'command_create_user' => 'Comando para criar o utilizador',
        'command_assign_permissions' => 'Comando para atribuir permissões',
        'cli_exit' => 'Para sair do mysql cli execute <code>exit</code>.',
        'external_access' => 'Acesso externo',
        'allow_external_access' => '
                                    <p>Talvez você precise permitir o acesso externo a essa instância do MySQL para permitir que os servidores se conectem a ele.</p>
                                    <br>
                                    <p>Para fazer isso, abra <code>my.cnf</code>, que muda de localização dependendo do seu sistema operacional e como o MySQL foi instalado. Você pode digitar find <code>/etc -iname my.cnf</code> para localizá-lo.</p>
                                    <br>
                                    <p>Abra <code>my.cnf</code>, adicione o texto abaixo ao final do arquivo e salve-o:<br>
                                    <code>[mysqld]<br>bind-address=0.0.0.0</code></p>
                                    <br>
                                    <p>Reinicie o MySQL/ MariaDB para aplicar essas alterações. Isso irá substituir a configuração padrão do MySQL, que por padrão só aceitará solicitações de localhost. Atualizar isso permitirá conexões em todas as interfaces e, portanto, conexões externas. Certifique-se de permitir a porta do MySQL (padrão 3306) no seu firewall.</p>                                ',
    ],
];
