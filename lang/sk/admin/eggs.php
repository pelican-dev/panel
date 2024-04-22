<?php

return [
    'notices' => [
        'imported' => 'Toto vajce a jeho potrebné premenné boli importované úspešne.',
        'updated_via_import' => 'Toto vajce bolo aktualizované pomocou nahraného súboru.',
        'deleted' => 'Požadované vajce bolo úspešne odstránené z panelu.',
        'updated' => 'Konfigurácia vajca bola aktualizovaná úspešne.',
        'script_updated' => 'Inštalačný skript vajca bol aktualizovaný a bude spustený vždy pri inštalácii servera.',
        'egg_created' => 'Nové vajce bolo znesené úspešne. Budete musieť reštartovať spustené daemony na aplikovanie nového vajca.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'Premenná ":variable" bola zmazaná a po prestavaní nebude pre servery dostupná.',
            'variable_updated' => 'Premenná ":variable" bola aktualizovaná. Servery, ktoré používajú danú premennú je potrebné prestavať pre aplikovanie zmien.',
            'variable_created' => 'Nová premenná bola úspešne vytvorená a priradená k tomuto vajcu.',
        ],
    ],
];
