<?php

return [
    'title' => 'Saúde',
    'results_refreshed' => 'Resultados do Exame de Saúde atualizados',
    'checked' => 'Resultados verificados de :time',
    'refresh' => 'Atualizar',
    'results' => [
        'cache' => [
            'label' => 'Cache',
            'ok' => 'Ok',
            'failed_retrieve' => 'Não foi possível definir ou recuperar o valor de cache da aplicação.',
            'failed' => 'Ocorreu uma exceção com o cache do aplicativo: :error',
        ],
        'database' => [
            'label' => 'Banco de dados',
            'ok' => 'Ok',
            'failed' => 'Não foi possível conectar ao banco de dados: :error',
        ],
        'debugmode' => [
            'label' => 'Modo de Depuração',
            'ok' => 'Modo de Depuração desativado',
            'failed' => 'O modo de depuração era esperado como :expected, mas na verdade foi :actual.',
        ],
        'environment' => [
            'label' => 'Ambiente',
            'ok' => 'Ok, Definido como :actual',
            'failed' => 'Ambiente está definido como :real , esperado :expected',
        ],
        'nodeversions' => [
            'label' => 'Versão do Node',
            'ok' => 'Os Nodes estão atualizados',
            'failed' => ':outdated/:all Nodes estão desatualizados',
            'no_nodes_created' => 'Nenhum Node criado',
            'no_nodes' => 'Sem Nodes',
            'all_up_to_date' => 'Tudo atualizado',
            'outdated' => ':outdated/:all desatualizado',
        ],
        'panelversion' => [
            'label' => 'Versão do Painel',
            'ok' => 'Painel está atualizado',
            'failed' => 'A versão instalada é :currentVersion, mas a última é :latestVersion',
            'up_to_date' => 'Atualizado',
            'outdated' => 'Desatualizado',
        ],
        'schedule' => [
            'label' => 'Agendamento',
            'ok' => 'Ok',
            'failed_last_ran' => 'A última execução do Agendamento foi maior que :time minutos atrás',
            'failed_not_ran' => 'O Agendamento ainda não foi executado.',
        ],
        'useddiskspace' => [
            'label' => 'Espaço em Disco',
        ],
    ],
    'checks' => [
        'successful' => 'Bem-sucedido',
        'failed' => 'Falhou',
    ],
];
