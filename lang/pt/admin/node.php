<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'O FQDN ou endereço IP providenciado não é valido.',
        'fqdn_required_for_ssl' => 'É necessário de um FQDN para ser utilizado o SSL.',
    ],
    'notices' => [
        'allocations_added' => 'As alocações foram adicionadas com sucesso no node.',
        'node_deleted' => 'O node foi removido com sucesso do painel.',
        'node_created' => 'O node foi criado com sucesso. Você pode automaticamente configurar esse node na máquina entrando na aba Configurações. <strong>Antes de adicionar os servidores, é necessário criar uma alocação com endereço IP da máquina e a porta.</strong>',
        'node_updated' => 'As informações do node foi atualizada. Caso foi feito uma configuração na máquina do node, será necessário reiniciar para aplicar as alterações.',
        'unallocated_deleted' => 'Foram removidos todas as portas não alocadas para <code>:ip</code>',
    ],
];
