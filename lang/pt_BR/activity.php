<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Falhou ao fazer login',
        'success' => 'Logou',
        'password-reset' => 'Resetou a senha',
        'checkpoint' => 'Autenticação em dois fatores solicitada',
        'recovery-token' => 'Usou token de recuperação da autenticação em dois fatores',
        'token' => 'Resolveu o desafio da autenticação de dois fatores',
        'ip-blocked' => 'Solicitação bloqueada de IP não listado para <b>:identifier</b>',
        'sftp' => [
            'fail' => 'Falhou no login via SFTP',
        ],
    ],
    'user' => [
        'account' => [
            'username-changed' => 'Alterou o nome de usuário de <b>:old</b> para <b>:new</b>',
            'email-changed' => 'Alterou o email de <b>:old</b> para <b>:new</b>',
            'password-changed' => 'Alterou a senha',
        ],
        'api-key' => [
            'create' => 'Criou uma Chave API <b>:identifier</b>',
            'delete' => 'Deletou uma Chave API <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => 'Adicionou uma Chave SSH <b>:fingerprint</b> para a conta',
            'delete' => 'Removou uma Chave SSH <b>:fingerprint</b> da conta',
        ],
        'two-factor' => [
            'create' => 'Ativou a autenticação em dois fatores',
            'delete' => 'Desativou a autenticação em dois fatores',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'Executou "<b>:command</b>" no servidor',
        ],
        'power' => [
            'start' => 'Iniciou o servidor',
            'stop' => 'Parou o servidor',
            'restart' => 'Reiniciou o servidor',
            'kill' => 'Encerrou o processo do servidor',
        ],
        'backup' => [
            'download' => 'Baixou o backup <b>:name</b>',
            'delete' => 'Deletou o backup <b>:name</b>',
            'restore' => 'Restaurou o backup <b>:name</b> (arquivos deletados: <b>:truncate</b>)',
            'restore-complete' => 'Completou a restauração do backup <b>:name</b>',
            'restore-failed' => 'Falhou ao completar a restauração do backup <b>:name</b>',
            'start' => 'Iniciou um novo backup <b>:name</b>',
            'complete' => 'Marcou o backup <b>:name</b> como completo',
            'fail' => 'Marcou o backup <b>:name</b> como falhado',
            'lock' => 'Bloqueou o backup <b>:name</b>',
            'unlock' => 'Desbloqueou o backup <b>:name</b>',
            'rename' => 'Renomeou o backup de "<b>:old_name</b>" para "<b>:new_name</b>"',
        ],
        'database' => [
            'create' => 'Criou uma base de dados <b>:name</b>',
            'rotate-password' => 'Gerou uma nova senha para a base de dados <b>:name</b>',
            'delete' => 'Deletou a base de dados <b>:name</b>',
        ],
        'file' => [
            'compress' => 'Comprimiu <b>:directory:files</b>|Comprimiu <b>:count</b> arquivos em <b>:directory</b>',
            'read' => 'Visualizou o conteúdo de <b>:file</b>',
            'copy' => 'Criou uma cópia de <b>:file</b>',
            'create-directory' => 'Criou um diretório <b>:directory:name</b>',
            'decompress' => 'Extraiu <b>:file</b> em <b>:directory</b>',
            'delete' => 'Deletou<b>:directory:files</b>|Deletou<b>:count</b> arquivos em <b>:directory</b>',
            'download' => 'Baixou <b>:file</b>',
            'pull' => 'Baixou um arquivo remoto de <b>:url</b> para <b>:directory</b>',
            'rename' => 'Moveu/ Renomeou <b>:from</b> para <b>:to</b>|Moveu/ Renomeou<b>:count</b> arquivos em <b>:directory</b>',
            'write' => 'Escreveu um novo conteúdo em <b>:file</b>',
            'upload' => 'Começou o upload de um arquivo',
            'uploaded' => 'Fez upload de <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => 'Acesso SFTP bloqueado devido a permissões',
            'create' => 'Criou<b>:files</b>|Criou<b>:count</b> novos arquivos',
            'write' => 'Modificou o conteúdo de <b>:files</b>|Modificou o conteúdo de <b>:count</b> arquivos',
            'delete' => 'Deletou<b>:files</b>|Deletou<b>:count</b> arquivos',
            'create-directory' => 'Criou o <b>:files</b> diretório|Criou <b>:count</b> diretórios',
            'rename' => 'Renomeou <b>:from</b> para <b>:to</b>|Renomeou ou moveu <b>:count</b> arquivos',
        ],
        'allocation' => [
            'create' => 'Adicionou <b>:allocation</b> para o servidor',
            'notes' => 'Atualizou as anotações para <b>:allocation</b> de "<b>:old</b>" para "<b>:new</b>"',
            'primary' => 'Definiu <b>:allocation</b> como a alocação primária',
            'delete' => 'Deletou a alocação <b>:allocation</b>',
        ],
        'schedule' => [
            'create' => 'Criou o agendamento <b>:name</b>',
            'update' => 'Atualizou o agendamento <b>:name</b>',
            'execute' => 'Executou manualmente o agendamento <b>:name</b>',
            'delete' => 'Deletou o agendamento <b>:name</b>',
        ],
        'task' => [
            'create' => 'Criou uma nova tarefa "<b>:action</b>" para o agendamento <b>:name</b>',
            'update' => 'Atualizou a tarefa "<b>:action</b>" para o agendamento <b>:name</b>',
            'delete' => 'Deletou a tarefa "<b>:action</b>" do agendamento <b>:name</b>',
        ],
        'settings' => [
            'rename' => 'Renomeou o servidor de "<b>:old</b>" para "<b>:new</b>"',
            'description' => 'Alterou a descrição do servidor de "<b>:old</b>" para "<b>:new</b>"',
            'reinstall' => 'Reinstalou o servidor',
        ],
        'startup' => [
            'edit' => 'Alterou a variável <b>:variable</b> de "<b>:old</b>" para "<b>:new</b>"',
            'image' => 'Atualizou a imagem Docker de <b>:old</b> para <b>:new</b>',
            'command' => 'Atualizou o comando de inicialização do servidor de <b>:old</b> para <b>:new</b>',
        ],
        'subuser' => [
            'create' => 'Adicionou <b>:email</b> como sub-usuário',
            'update' => 'Atualizou as permissões do sub-usuário <b>:email</b>',
            'delete' => 'Removeu <b>:email</b> como sub-usuário',
        ],
        'crashed' => 'Servidor Falhou',
    ],
];
