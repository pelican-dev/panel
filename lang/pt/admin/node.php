<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'O FQDN ou endereço IP fornecido não resolve para um endereço IP válido.',
        'fqdn_required_for_ssl' => 'Um nome de domínio totalmente qualificado que resolve para um endereço IP público é necessário para usar SSL neste nó.',
    ],
    'notices' => [
        'allocations_added' => 'As alocações foram adicionadas com sucesso a este nó.',
        'node_deleted' => 'O nó foi removido com sucesso do painel.',
        'node_created' => 'Nó criado com sucesso. Você pode configurar automaticamente o daemon nesta máquina visitando a aba "Configuração". <strong>Antes de adicionar quaisquer servidores, você deve primeiro alocar pelo menos um endereço IP e porta.</strong>',
        'node_updated' => 'As informações do nó foram atualizadas. Se alguma configuração do daemon foi alterada, você precisará reiniciá-lo para que as alterações tenham efeito.',
        'unallocated_deleted' => 'Todos os portas não alocadas para <code>:ip</code> foram excluídas.',
    ],
];
