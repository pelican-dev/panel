<?php

return [
    'user' => [
        'search_users' => 'Insira um Nome de Usuário, ID de Usuário ou Endereço Email',
        'select_search_user' => 'ID do usuário para apagar (Digite \'0\' para pesquisar novamente)',
        'deleted' => 'Usuário apagado do Painel com sucesso.',
        'confirm_delete' => 'Você tem certeza que deseja apagar esse usuário do Painel?',
        'no_users_found' => 'Nenhum usuário foi encontrado com o termo de pesquisa fornecido.',
        'multiple_found' => 'Foram encontradas várias contas para o usuário fornecido, não é possível excluir um usuário devido à flag --no-interaction',
        'ask_admin' => 'Este usuário é um administrador?',
        'ask_email' => 'Endereço de Email',
        'ask_username' => 'Nome de Usuário',
        'ask_password' => 'Senha',
        'ask_password_tip' => 'Se você gostaria de criar uma conta com uma senha aleatória enviada por e-mail para o usuário, execute novamente este comando (CTRL+C) e passe a flag `--no-password`.',
        'ask_password_help' => 'Senhas devem conter pelo menos 8 caracteres e pelo menos uma letra maiúscula e um número.',
        '2fa_help_text' => 'Este comando desativará a autenticação de dois fatores para a conta de um usuário se estiver ativada. Isso só deve ser usado como um comando de recuperação de conta se o usuário tiver perdido completamente o acesso à conta. Se isso não é o que você queria fazer, pressione CTRL+C para sair desse processo.',
        '2fa_disabled' => 'A autenticação de dois fatores foi desativada para o email :email.',
    ],
    'schedule' => [
        'output_line' => 'Executando a primeira tarefa de :schedule (:id).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Apagando arquivo de backup do serviço :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Pedido de reconstrução para ":name" (#:id) no Node ":node" falhou com o erro: :message',
        'reinstall' => [
            'failed' => 'Pedido de reinstalação para ":name" (#:id) no Node ":node" falhou com o erro: :message',
            'confirm' => 'Você está prestes a reinstalar em um grupo de servidores. Quer continuar?',
        ],
        'power' => [
            'confirm' => 'Você está prestes a executar a ação :action em :count servidores. Deseja continuar?',
            'action_failed' => 'Pedido de ação de energia para ":name" (#:id) no Node ":node" falhou com o erro: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'Host SMTP (ex. smtp.gmail.com)',
            'ask_smtp_port' => 'Porta SMTP',
            'ask_smtp_username' => 'Nome de Usuário SMTP',
            'ask_smtp_password' => 'Senha SMTP',
            'ask_mailgun_domain' => 'Domínio do Mailgun',
            'ask_mailgun_endpoint' => 'Endpoint do Mailgun',
            'ask_mailgun_secret' => 'Secret do Mailgun',
            'ask_mandrill_secret' => 'Secret do Mandrill',
            'ask_postmark_username' => 'Chave API do Postmark',
            'ask_driver' => 'Qual driver deve ser usado para enviar emails?',
            'ask_mail_from' => 'Endereço de e-mail de onde os e-mails devem ser enviados',
            'ask_mail_name' => 'Nome que deve aparecer nos e-mails',
            'ask_encryption' => 'Método de criptografia a ser usado',
        ],
    ],
];
