<?php

return [
    'daemon_connection_failed' => 'Ocorreu uma exceção ao tentar se comunicar com o Daemon, resultando em um código de resposta HTTP/:code. Essa exceção foi registrada nos logs.',
    'node' => [
        'servers_attached' => 'Um Node não pode ter servidores vinculados a ele para ser excluído.',
        'error_connecting' => 'Erro ao conectar-se ao :node',
        'daemon_off_config_updated' => 'A configuração do Daemon <strong>foi atualizada</strong>, porém ocorreu um erro ao tentar atualizar automaticamente o arquivo de configuração no Daemon. Você precisará atualizar manualmente o arquivo de configuração (config.yml) para que as alterações tenham efeito.',
    ],
    'allocations' => [
        'server_using' => 'Um servidor está atualmente atribuído a esta alocação. Uma alocação só pode ser excluída se nenhum servidor estiver atribuído a ela.',
        'too_many_ports' => 'Adicionar mais de 1000 portas em um único intervalo de uma vez não é suportado.',
        'invalid_mapping' => 'O mapeamento fornecido para :port é inválido e não pôde ser processado.',
        'cidr_out_of_range' => 'A notação CIDR permite apenas máscaras entre /25 e /32.',
        'port_out_of_range' => 'As portas em uma alocação devem ser maiores ou iguais a 1024 e menores ou iguais a 65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'Um Egg com servidores ativos vinculados a ele não pode ser excluído do Painel.',
        'invalid_copy_id' => 'O Egg selecionado para copiar o script ou não existe, ou está copiando um script de outro Egg.',
        'has_children' => 'Este Egg é pai de um ou mais outros Eggs. Por favor, exclua esses Eggs antes de excluir este.',
    ],
    'variables' => [
        'env_not_unique' => 'A variável de ambiente :name deve ser única para este Egg.',
        'reserved_name' => 'A variável de ambiente :name é protegida e não pode ser atribuída a uma variável.',
        'bad_validation_rule' => 'A regra de validação ":rule" não é uma regra válida para esta aplicação.',
    ],
    'importer' => [
        'json_error' => 'Ocorreu um erro ao tentar analisar o arquivo JSON: :error.',
        'file_error' => 'O arquivo JSON fornecido não é válido.',
        'invalid_json_provided' => 'O arquivo JSON fornecido não está em um formato reconhecível.',
    ],
    'subusers' => [
        'editing_self' => 'Editar sua própria conta de sub-usuário não é permitido.',
        'user_is_owner' => 'Você não pode adicionar o proprietário do servidor como sub-usuário deste servidor.',
        'subuser_exists' => 'Um usuário com esse endereço de e-mail já está designado como sub-usuário deste servidor.',
    ],
    'databases' => [
        'delete_has_databases' => 'Não é possível excluir um servidor host de banco de dados que possui bancos de dados ativos vinculados a ele.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'O tempo máximo de intervalo para uma tarefa encadeada é de 15 minutos.',
    ],
    'locations' => [
        'has_nodes' => 'Não é possível excluir um local que possui Nodes ativos vinculados a ele.',
    ],
    'users' => [
        'is_self' => 'Não é possível excluir sua própria conta de usuário.',
        'has_servers' => 'Não é possível excluir um usuário que possui servidores ativos vinculados à sua conta. Por favor, exclua os servidores dele antes de continuar.',
        'node_revocation_failed' => 'Falha ao revogar chaves no <a href=":link">Node #:node</a>. \:error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Nenhum Node que atenda aos requisitos especificados para implantação automática foi encontrado.',
        'no_viable_allocations' => 'Nenhuma alocação que atenda aos requisitos para implantação automática foi encontrada.',
    ],
    'api' => [
        'resource_not_found' => 'O recurso solicitado não existe neste servidor.',
    ],
    'mount' => [
        'servers_attached' => 'Uma montagem não deve ter servidores anexados a ela para ser excluída.',
    ],
    'server' => [
        'marked_as_failed' => 'Este servidor ainda não concluiu seu processo de instalação, por favor, tente novamente mais tarde.',
    ],
];
