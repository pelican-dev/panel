<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Falha ao fazer login',
        'success' => 'Logado',
        'password-reset' => 'Redefinição de senha',
        'reset-password' => 'Solicitou redefinição de senha',
        'checkpoint' => 'Solicitação de autenticação de dois fatores',
        'recovery-token' => 'Usado token de recuperação de dois fatores',
        'token' => 'Resolveu desafio de dois fatores',
        'ip-blocked' => 'Solicitação bloqueada do endereço IP não listado para :identifier',
        'sftp' => [
            'fail' => 'Falha ao fazer login no SFTP',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Alterou o e-mail de :old para :new',
            'password-changed' => 'Alterou a senha',
        ],
        'api-key' => [
            'create' => 'Criou uma nova chave de API :identifier',
            'delete' => 'Excluiu a chave de API :identifier',
        ],
        'ssh-key' => [
            'create' => 'Adicionou a chave SSH :fingerprint à conta',
            'delete' => 'Removeu a chave SSH :fingerprint da conta',
        ],
        'two-factor' => [
            'create' => 'Habilitou autenticação de dois fatores',
            'delete' => 'Desabilitou autenticação de dois fatores',
        ],
    ],
    'server' => [
        'reinstall' => 'Reinstalou o servidor',
        'console' => [
            'command' => 'Executou ":command" no servidor',
        ],
        'power' => [
            'start' => 'Iniciou o servidor',
            'stop' => 'Parou o servidor',
            'restart' => 'Reiniciou o servidor',
            'kill' => 'Encerrou o processo do servidor',
        ],
        'backup' => [
            'download' => 'Baixou o backup :name',
            'delete' => 'Excluiu o backup :name',
            'restore' => 'Restaurou o backup :name (arquivos excluídos: :truncate)',
            'restore-complete' => 'Restauração completa do backup :name',
            'restore-failed' => 'Falha ao restaurar o backup :name',
            'start' => 'Iniciou um novo backup :name',
            'complete' => 'Marcou o backup :name como completo',
            'fail' => 'Marcou o backup :name como falhou',
            'lock' => 'Travou o backup :name',
            'unlock' => 'Destravou o backup :name',
        ],
        'database' => [
            'create' => 'Criou um novo banco de dados :name',
            'rotate-password' => 'Senha rotacionada para o banco de dados :name',
            'delete' => 'Excluiu o banco de dados :name',
        ],
        'file' => [
            'compress_one' => 'Compactou :directory:file',
            'compress_other' => 'Compactou :count arquivos em :directory',
            'read' => 'Visualizou o conteúdo de :file',
            'copy' => 'Criou uma cópia de :file',
            'create-directory' => 'Criou o diretório :directory:name',
            'decompress' => 'Descompactou :files em :directory',
            'delete_one' => 'Excluiu :directory:files.0',
            'delete_other' => 'Excluiu :count arquivos em :directory',
            'download' => 'Baixou :file',
            'pull' => 'Baixou um arquivo remoto de :url para :directory',
            'rename_one' => 'Renomeou :directory:files.0.de para :directory:files.0.para',
            'rename_other' => 'Renomeou :count arquivos em :directory',
            'write' => 'Escreveu novo conteúdo em :file',
            'upload' => 'Iniciou upload de arquivo',
            'uploaded' => 'Carregou :directory:file',
        ],
        'sftp' => [
            'denied' => 'Acesso SFTP bloqueado devido a permissões',
            'create_one' => 'Criou :files.0',
            'create_other' => 'Criou :count novos arquivos',
            'write_one' => 'Modificou o conteúdo de :files.0',
            'write_other' => 'Modificou o conteúdo de :count arquivos',
            'delete_one' => 'Excluiu :files.0',
            'delete_other' => 'Excluiu :count arquivos',
            'create-directory_one' => 'Criou o diretório :files.0',
            'create-directory_other' => 'Criou :count diretórios',
            'rename_one' => 'Renomeou :files.0.de para :files.0.para',
            'rename_other' => 'Renomeou ou moveu :count arquivos',
        ],
        'allocation' => [
            'create' => 'Adicionou :allocation ao servidor',
            'notes' => 'Atualizou as notas para :allocation de ":old" para ":new"',
            'primary' => 'Definiu :allocation como alocação primária do servidor',
            'delete' => 'Excluiu a alocação :allocation',
        ],
        'schedule' => [
            'create' => 'Criou o agendamento :name',
            'update' => 'Atualizou o agendamento :name',
            'execute' => 'Executou manualmente o agendamento :name',
            'delete' => 'Excluiu o agendamento :name',
        ],
        'task' => [
            'create' => 'Criou uma nova tarefa ":action" para o agendamento :name',
            'update' => 'Atualizou a tarefa ":action" para o agendamento :name',
            'delete' => 'Excluiu uma tarefa para o agendamento :name',
        ],
        'settings' => [
            'rename' => 'Renomeou o servidor de :old para :new',
            'description' => 'Alterou a descrição do servidor de :old para :new',
        ],
        'startup' => [
            'edit' => 'Alterou a variável :variable de ":old" para ":new"',
            'image' => 'Atualizou a Imagem Docker do servidor de :old para :new',
        ],
        'subuser' => [
            'create' => 'Adicionou :email como um subusuário',
            'update' => 'Atualizou as permissões do subusuário para :email',
            'delete' => 'Removeu :email como um subusuário',
        ],
    ],
];
