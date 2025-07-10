<?php

return [
    'title' => 'Стан системи',
    'results_refreshed' => 'Результати перевірки стану оновлено',
    'checked' => 'Перевірені результати від :time',
    'refresh' => 'Оновити',
    'results' => [
        'cache' => [
            'label' => 'Кеш',
            'ok' => 'Ок',
            'failed_retrieve' => 'Не вдалося встановити або отримати значення кешу застосунку.',
            'failed' => 'Сталася помилка в кеші застосунку: :error',
        ],
        'database' => [
            'label' => 'База даних',
            'ok' => 'Ок',
            'failed' => 'Не вдалося під\'єднатися до бази даних: :error',
        ],
        'debugmode' => [
            'label' => 'Режим налагодження',
            'ok' => 'Режим налагодження вимкнено',
            'failed' => 'Очікувалося значення :expected, але отримано :actual',
        ],
        'environment' => [
            'label' => 'Середовище',
            'ok' => 'Ок, встановлено :actual',
            'failed' => 'Середовище встановлено як :actual, очікувалося :expected',
        ],
        'nodeversions' => [
            'label' => 'Версії вузлів',
            'ok' => 'Всі вузли оновлені',
            'failed' => ':outdated із :all вузлів застарілі',
            'no_nodes_created' => 'Не створено жодного вузла',
            'no_nodes' => 'Немає вузлів',
            'all_up_to_date' => 'Всі актуальні',
            'outdated' => ':outdated із :all застарілі',
        ],
        'panelversion' => [
            'label' => 'Версія панелі',
            'ok' => 'Панель останньої версії',
            'failed' => 'Встановлена версія :currentVersion, але остання доступна версія :latestVersion',
            'up_to_date' => 'Остання версія',
            'outdated' => 'Застаріла',
        ],
        'schedule' => [
            'label' => 'Розклад',
            'ok' => 'Ок',
            'failed_last_ran' => 'Останнє виконання розкладу було більше ніж :time хвилин тому',
            'failed_not_ran' => 'Розклад ще не виконувався.',
        ],
        'useddiskspace' => [
            'label' => 'Дисковий простір',
        ],
    ],
    'checks' => [
        'successful' => 'Успішно',
        'failed' => 'Помилка',
    ],
];
