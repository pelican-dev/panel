<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => 'Forneça o endereço de email que os Eggs exportados por esse painel devem ter. Esse deve ser um endereço de email válido.',
            'url' => 'A URL da aplicação DEVE começar com https:// ou http://, dependendo se você está usando SSL ou não. Se você não incluir o esquema, seus e-mails e outros conteúdos irão apontar para o local incorreto.',
            'timezone' => 'O fuso horário deve corresponder a um dos fusos horários suportados pelo PHP. Se você não tiver certeza, consulte: https://php.net/manual/en/timezones.php.',
        ],
        'redis' => [
            'note' => 'Você selecionou o driver Redis para uma ou mais opções, por favor forneça as informações de conexão válidas abaixo. Na maioria dos casos, você pode usar os valores padrão fornecidos, a menos que tenha modificado sua configuração.',
            'comment' => 'Por padrão, uma instância do servidor Redis usa o nome de usuário default e não possui senha, pois está sendo executada localmente e é inacessível ao mundo externo. Se este for o seu caso, basta pressionar Enter sem digitar nenhum valor.',
            'confirm' => 'Parece que o campo :field já está definido para o Redis. Você gostaria de alterá-lo?',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => 'É altamente recomendável não usar "localhost" como host do banco de dados, pois observamos problemas frequentes de conexão via socket. Se quiser usar uma conexão local, utilize "127.0.0.1".',
        'DB_USERNAME_note' => 'Usar a conta root para conexões MySQL não é apenas altamente desencorajado, como também não é permitido por este aplicativo. Você precisará criar um usuário MySQL específico para este software.',
        'DB_PASSWORD_note' => 'Parece que você já tem uma senha de conexão MySQL definida, gostaria de alterá-la?',
        'DB_error_2' => 'Suas credenciais de conexão NÃO foram salvas. Você precisará fornecer informações de conexão válidas antes de prosseguir.',
        'go_back' => 'Voltar e tentar novamente',
    ],
    'make_node' => [
        'name' => 'Insira um identificador curto usado para distinguir este Node dos outros',
        'description' => 'Insira uma descrição para identificar o Node',
        'scheme' => 'Por favor, digite https para SSL ou http para uma conexão sem conexão SSL',
        'fqdn' => 'Digite um nome de domínio (ex: node.example.com) a ser usado para conexão com o Daemon. Um endereço IP só pode ser usado se você não estiver usando SSL para este Node',
        'public' => 'Este Node deve ser público? Como nota, definir um Node como privado irá negar a capacidade de auto-implantar para este Node.',
        'behind_proxy' => 'Seu FQDN está por trás de um proxy?',
        'maintenance_mode' => 'O modo de manutenção deve ser habilitado?',
        'memory' => 'Digite a quantidade máxima de memória',
        'memory_overallocate' => 'Informe a quantidade de memória a ser superalocada. Use -1 para desativar a verificação e 0 para impedir a criação de novos servidores.',
        'disk' => 'Insira a quantidade máxima de espaço em disco',
        'disk_overallocate' => 'Informe a quantidade de disco a ser superalocada. Use -1 para desativar a verificação e 0 para impedir a criação de novos servidores.',
        'cpu' => 'Insira a quantidade máxima de CPU',
        'cpu_overallocate' => 'Informe a quantidade de CPU a ser superalocada. Use -1 para desativar a verificação e 0 para impedir a criação de novos servidores.',
        'upload_size' => "'Digite o tamanho máximo para upload de arquivos",
        'daemonListen' => 'Digite a porta do Daemon',
        'daemonConnect' => 'Digite a conexão da porta do daemon (pode ser a mesma que a porta de escuta)',
        'daemonSFTP' => 'Digite a porta do Daemon SFTP',
        'daemonSFTPAlias' => 'Digite o alias do Daemon SFTP (pode estar vazio)',
        'daemonBase' => 'Digite a pasta base',
        'success' => 'Um novo Node foi criado com sucesso com o nome :name e tem um ID de :id',
    ],
    'node_config' => [
        'error_not_exist' => 'O Node selecionado não existe.',
        'error_invalid_format' => 'Formato inválido especificado. As opções válidas são YAML e JSON',
    ],
    'key_generate' => [
        'error_already_exist' => 'Parece que você já configurou uma chave de criptografia da aplicação. Continuar com este processo irá sobrescrever essa chave e causar corrupção dos dados já criptografados. NÃO CONTINUE, A MENOS QUE SAIBA EXATAMENTE O QUE ESTÁ FAZENDO.',
        'understand' => 'Eu entendo as consequências de executar este comando e aceito toda a responsabilidade pela perda dos dados criptografados.',
        'continue' => 'Tem certeza que deseja continuar? Alterar a chave de criptografia da aplicação VAI CAUSAR PERDA DE DADOS.',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => 'Não há tarefas agendadas para servidores que precisem ser executadas.',
            'error_message' => 'Ocorreu um erro ao processar o agendamento: ',
        ],
    ],
];
