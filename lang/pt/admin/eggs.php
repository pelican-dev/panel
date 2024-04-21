<?php

return [
    'notices' => [
        'imported' => 'As eggs e as suas variáveis de ambiente foram importadas com sucesso.',
        'updated_via_import' => 'Essa egg foi atualizada usando o arquivo fornecido.',
        'deleted' => 'A egg solicitada foi removida com sucesso do Painel.',
        'updated' => 'As configurações da egg foi atualizada com sucesso.',
        'script_updated' => 'O script de instação da egg foi atualizado e poderá ser executado quando os servidores forem instalados.',
        'egg_created' => 'Um novo egg \'foi criado com sucesso. Reinicie os daemons em execução para aplicar essa nova egg.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'A variável ":variable" foi removida com sucesso e não estará mais disponível para os servidores após a reinstalação.',
            'variable_updated' => 'A variável ":variable" foi atualizada. Reinstale os servidores utilizando essa variável para as aplicações serem alteradas.',
            'variable_created' => 'Essa variável foi criada com sucesso e vinculada com a egg.',
        ],
    ],
];
