<?php

return [
    'user' => [
        'search_users' => 'Digite um Nome de Usuário, ID de Usuário ou Endereço de E-mail',
        'select_search_user' => 'ID do usuário para excluir (Digite \'0\' para pesquisar novamente)',
        'deleted' => 'Usuário excluído com sucesso do Painel.',
        'confirm_delete' => 'Tem certeza de que deseja excluir este usuário do Painel?',
        'no_users_found' => 'Nenhum usuário foi encontrado para o termo de pesquisa fornecido.',
        'multiple_found' => 'Múltiplas contas foram encontradas para o usuário fornecido, impossível excluir um usuário por causa da sinalização --no-interaction.',
        'ask_admin' => 'Este usuário é um administrador?',
        'ask_email' => 'Endereço de E-mail',
        'ask_username' => 'Nome de Usuário',
        'ask_name_first' => 'Primeiro Nome',
        'ask_name_last' => 'Sobrenome',
        'ask_password' => 'Senha',
        'ask_password_tip' => 'Se você deseja criar uma conta com uma senha aleatória enviada por e-mail para o usuário, execute novamente este comando (CTRL+C) e passe a sinalização `--no-password`.',
        'ask_password_help' => 'As senhas devem ter pelo menos 8 caracteres de comprimento e conter pelo menos uma letra maiúscula e um número.',
        '2fa_help_text' => [
            'Este comando desativará a autenticação de dois fatores para a conta de um usuário se estiver ativada. Isso só deve ser usado como um comando de recuperação de conta se o usuário estiver bloqueado em sua conta.',
            'Se isso não for o que você queria fazer, pressione CTRL+C para sair deste processo.',
        ],
        '2fa_disabled' => 'A autenticação de dois fatores foi desativada para :email.',
    ],
    'schedule' => [
        'output_line' => 'Despachando tarefa para o primeiro item em `:schedule` (:hash).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Excluindo arquivo de backup do serviço :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Falha na reconstrução para ":name" (#:id) no nó ":node" com erro: :message',
        'reinstall' => [
            'failed' => 'Falha na reinstalação para ":name" (#:id) no nó ":node" com erro: :message',
            'confirm' => 'Você está prestes a reinstalar em um grupo de servidores. Deseja continuar?',
        ],
        'power' => [
            'confirm' => 'Você está prestes a realizar uma :action em :count servidores. Deseja continuar?',
            'action_failed' => 'Falha na ação de energia para ":name" (#:id) no nó ":node" com erro: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'Host SMTP (por exemplo, smtp.gmail.com)',
            'ask_smtp_port' => 'Porta SMTP',
            'ask_smtp_username' => 'Nome de Usuário SMTP',
            'ask_smtp_password' => 'Senha SMTP',
            'ask_mailgun_domain' => 'Domínio do Mailgun',
            'ask_mailgun_endpoint' => 'Endpoint do Mailgun',
            'ask_mailgun_secret' => 'Segredo do Mailgun',
            'ask_mandrill_secret' => 'Segredo do Mandrill',
            'ask_postmark_username' => 'Chave da API do Postmark',
            'ask_driver' => 'Qual driver deve ser usado para enviar e-mails?',
            'ask_mail_from' => 'Endereço de e-mail de onde os e-mails devem ser enviados',
            'ask_mail_name' => 'Nome que deve aparecer nos e-mails',
            'ask_encryption' => 'Método de criptografia a ser usado',
        ],
    ],
];
