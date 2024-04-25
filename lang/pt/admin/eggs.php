<?php

return [
    'notices' => [
        'imported' => 'Ovos e suas variáveis associadas foram importados com sucesso.',
        'updated_via_import' => 'Este ovo foi atualizado usando o arquivo fornecido.',
        'deleted' => 'O ovo solicitado foi excluído com sucesso do painel.',
        'updated' => 'A configuração do ovo foi atualizada com sucesso.',
        'script_updated' => 'O script de instalação do ovo foi atualizado e será executado sempre que os servidores forem instalados.',
        'egg_created' => 'Um novo ovo foi criado com sucesso. Você precisará reiniciar quaisquer servidores em execução para aplicar este novo ovo.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'A variável ":variable" foi excluída e não estará mais disponível para os servidores uma vez reconstruídos.',
            'variable_updated' => 'A variável ":variable" foi atualizada. Você precisará reconstruir quaisquer servidores que usem esta variável para aplicar as mudanças.',
            'variable_created' => 'Nova variável foi criada com sucesso e atribuída a este ovo.',
        ],
    ],
];
