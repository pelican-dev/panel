<?php

return [
    'title' => 'Здраве',
    'results_refreshed' => 'Резултатите от проверката на състоянието са актуализирани',
    'checked' => 'Проверени резултати от :time .',
    'refresh' => 'Презареждане',
    'results' => [
        'cache' => [
            'label' => 'Кеш',
            'ok' => 'Добре',
            'failed_retrieve' => 'Не можа да се зададе или извлече стойност на кеша на приложението.',
            'failed' => 'Възникна изключение с кеша на приложението: :error',
        ],
        'database' => [
            'label' => 'База данни',
            'ok' => 'Добре',
            'failed' => 'Няма връзка с базата данни: :error',
        ],
        'debugmode' => [
            'label' => 'Режим за отстраняване на грешки',
            'ok' => 'Режимът за отстраняване на грешки е деактивиран',
            'failed' => 'Очакваше се режимът на грешки да бъде :expected, но всъщност беше :actual',
        ],
        'environment' => [
            'label' => 'Обстановка',
            'ok' => 'Добре, зададено на :actual',
            'failed' => 'Средата е настроена на :actual, Очакван :expected',
        ],
        'nodeversions' => [
            'label' => 'Версии на nodo-вете.',
            'ok' => 'Node-овете са актуални.',
            'failed' => ':outdated/:all Nodo-вете са неактуални.',
            'no_nodes_created' => 'Няма създадени nodo-ве.',
            'no_nodes' => 'Няма nodo-ве.',
            'all_up_to_date' => 'Всичко е актуално.',
            'outdated' => ':outdated/:all неактуален.',
        ],
        'panelversion' => [
            'label' => 'Панелна версия',
            'ok' => 'Панелът е актуален',
            'failed' => 'Инсталираната версия е :currentVersion, но най-новата е :latestVersion',
            'up_to_date' => 'Нов',
            'outdated' => '',
        ],
        'schedule' => [
            'label' => 'Задача.',
            'ok' => 'Добре.',
            'failed_last_ran' => 'Последното начало на задачата е по-дълго от :time минути',
            'failed_not_ran' => 'Задачата не е започнала все-още.',
        ],
        'useddiskspace' => [
            'label' => 'Място за съхранение.',
        ],
    ],
    'checks' => [
        'successful' => 'Успешно.',
        'failed' => 'Провалени :checks',
    ],
];
