<?php

return [
    'daemon_connection_failed' => 'Houve uma exceção ao tentar se comunicar com o daemon resultando em um código de resposta HTTP/:code. Esta exceção foi registrada.',
    'node' => [
        'servers_attached' => 'Um nó deve não ter nenhum servidor vinculado a ele para ser excluído.',
        'daemon_off_config_updated' => 'A configuração do daemon <strong>foi atualizada</strong>, no entanto, ocorreu um erro ao tentar atualizar automaticamente o arquivo de configuração no Daemon. Você precisará atualizar manualmente o arquivo de configuração (config.yml) para o daemon aplicar essas alterações.',
    ],
    'allocations' => [
        'server_using' => 'Um servidor está atualmente atribuído a esta alocação. Uma alocação só pode ser excluída se nenhum servidor estiver atualmente atribuído.',
        'too_many_ports' => 'Adicionar mais de 1000 portas em um único intervalo de uma só vez não é suportado.',
        'invalid_mapping' => 'O mapeamento fornecido para :port era inválido e não pôde ser processado.',
        'cidr_out_of_range' => 'A notação CIDR permite apenas máscaras entre /25 e /32.',
        'port_out_of_range' => 'As portas em uma alocação devem ser maiores que 1024 e menores ou iguais a 65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'Um Egg com servidores ativos vinculados a ele não pode ser excluído do Painel.',
        'invalid_copy_id' => 'O Egg selecionado para copiar um script ou não existe, ou está copiando um script em si.',
        'has_children' => 'Este Egg é pai de um ou mais outros Eggs. Por favor, exclua esses Eggs antes de excluir este Egg.',
    ],
    'variables' => [
        'env_not_unique' => 'A variável de ambiente :name deve ser única para este Egg.',
        'reserved_name' => 'A variável de ambiente :name é protegida e não pode ser atribuída a uma variável.',
        'bad_validation_rule' => 'A regra de validação ":rule" não é uma regra válida para este aplicativo.',
    ],
    'importer' => [
        'json_error' => 'Houve um erro ao tentar analisar o arquivo JSON: :error.',
        'file_error' => 'O arquivo JSON fornecido não era válido.',
        'invalid_json_provided' => 'O arquivo JSON fornecido não está em um formato reconhecível.',
    ],
    'subusers' => [
        'editing_self' => 'Editar sua própria conta de subusuário não é permitido.',
        'user_is_owner' => 'Você não pode adicionar o proprietário do servidor como um subusuário para este servidor.',
        'subuser_exists' => 'Um usuário com esse endereço de e-mail já está atribuído como um subusuário para este servidor.',
    ],
    'databases' => [
        'delete_has_databases' => 'Não é possível excluir um servidor de banco de dados que possui bancos de dados ativos vinculados a ele.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'O tempo máximo de intervalo para uma tarefa encadeada é de 15 minutos.',
    ],
    'locations' => [
        'has_nodes' => 'Não é possível excluir uma localização que possui nós ativos anexados a ela.',
    ],
    'users' => [
        'node_revocation_failed' => 'Falha ao revogar chaves em <a href=":link">Node #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Nenhum nó que atenda aos requisitos especificados para implantação automática pôde ser encontrado.',
        'no_viable_allocations' => 'Nenhuma alocação que satisfaça os requisitos para implantação automática foi encontrada.',
    ],
    'api' => [
        'resource_not_found' => 'O recurso solicitado não existe neste servidor.',
    ],
];
