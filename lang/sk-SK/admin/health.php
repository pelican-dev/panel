<?php

return [
    'title' => 'Zdravie',
    'results_refreshed' => 'Výsledky kontroly zdravia boli aktualizované',
    'checked' => 'Skontrolované výsledky z',
    'refresh' => 'Obnoviť',
    'results' => [
        'cache' => [
            'label' => 'Vyrovnávacia pamäť',
            'ok' => 'Ok',
            'failed_retrieve' => 'Nepodarilo sa nastaviť alebo získať hodnotu z aplikačnej vyrovnávacej pamäte.',
            'failed' => 'V aplikácii došlo k chybe s vyrovnávacou pamäťou: :error',
        ],
        'database' => [
            'label' => 'Databáza',
            'ok' => 'Ok',
            'failed' => 'Nepodarilo sa pripojiť k databáze: :error',
        ],
        'debugmode' => [
            'label' => 'Režim ladenia',
            'ok' => 'Režim ladenia je vypnutý',
            'failed' => 'Režim ladenia bol predpokladaný :expected, ale reálne bol :actual',
        ],
        'environment' => [
            'label' => 'Prostredie',
            'ok' => 'Ok, nastaviť na :actual',
            'failed' => 'Prostredie je nastavené na :actual, bolo predpokladané :expected',
        ],
        'nodeversions' => [
            'label' => 'Verzie uzlov',
            'ok' => 'Uzly sú aktualizované',
            'failed' => ':outdated/:all uzly sú zastaralé',
            'no_nodes_created' => 'Žiadne vytvorené uzly',
            'no_nodes' => 'Žiadne uzly',
            'all_up_to_date' => 'Všetko je aktualizované',
            'outdated' => ':outdated/:all zastaralé',
        ],
        'panelversion' => [
            'label' => 'Verzia panelu',
            'ok' => 'Panel je aktualizovaný',
            'failed' => 'Nainštalovaná verzia je :current , ale najnovšia verzia je :latestVersion',
            'up_to_date' => 'Aktualizované',
            'outdated' => 'Zastaralé',
        ],
        'schedule' => [
            'label' => 'Naplánovať',
            'ok' => 'Ok',
            'failed_last_ran' => 'Posledné spustenie naplánovanej udalosti bolo viac ako pred :time minútami',
            'failed_not_ran' => 'Naplánovaná udalosť sa ešte nespustila.',
        ],
        'useddiskspace' => [
            'label' => 'Miesto na disku',
        ],
    ],
    'checks' => [
        'successful' => 'Úspešné',
        'failed' => 'Zlyhalo',
    ],
];
