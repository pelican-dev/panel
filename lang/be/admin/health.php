<?php

return [
    'title' => 'Стан',
    'results_refreshed' => 'Рэкамендацыі па праверцы стану абноўлены',
    'checked' => 'Правераныя вынікі ад :time',
    'refresh' => 'Абнавіць',
    'results' => [
        'cache' => [
            'label' => 'Кэш',
            'ok' => 'Добра',
            'failed_retrieve' => 'Няўдалося ўсталяваць або атрымаць значэнне кэша прыкладання.',
            'failed' => 'Адбылася памылка з кэшам прыкладання: :error',
        ],
        'database' => [
            'label' => 'База даных',
            'ok' => 'Добра',
            'failed' => 'Няўдалося падключыцца да базы даных: :error',
        ],
        'debugmode' => [
            'label' => 'Рэжым адладки',
            'ok' => 'Рэжым адладки адключаны',
            'failed' => 'Чакалася, што рэжым адладки будзе: :expected, але на самой справе быў: :actual',
        ],
        'environment' => [
            'label' => 'Асяроддзе',
            'ok' => 'Добра, усталявана на :actual',
            'failed' => 'Асяроддзе ўсталявана на :actual, чакалася :expected',
        ],
        'nodeversions' => [
            'label' => 'Версіі вузлоў',
            'ok' => 'Вузлы абноўлены',
            'failed' => ':outdated:/:all вузлы састарэлі',
            'no_nodes_created' => 'Вузлоў няма',
            'no_nodes' => 'Вузлоў няма',
            'all_up_to_date' => 'Усе абноўлена',
            'outdated' => ':outdated:/:all састарэла',
        ],
        'panelversion' => [
            'label' => 'Версія панэлі',
            'ok' => 'Панэль абноўлена да актуальнай версіі',
            'failed' => 'Усталяваная версія: :currentVersion, але апошняя: :latestVersion',
            'up_to_date' => 'Абноўлена',
            'outdated' => 'Састарэла',
        ],
        'schedule' => [
            'label' => 'Задача',
            'ok' => 'Добра',
            'failed_last_ran' => 'Апошні запуск задачы адбыўся больш за :time хвілін таму назад',
            'failed_not_ran' => 'Задача яшчэ не выканана',
        ],
        'useddiskspace' => [
            'label' => 'Месца на дыску',
        ],
    ],
    'checks' => [
        'successful' => 'Паспяхова',
        'failed' => 'Няўдалося',
    ],
];
