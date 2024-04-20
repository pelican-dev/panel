<?php

return [
    'notices' => [
        'imported' => 'Toto vajce a jeho potrebné premenné boli importované úspešne.',
        'updated_via_import' => 'Toto vajce bolo aktualizované pomocou nahraného súboru.',
        'deleted' => 'Požadované vajce bolo úspešne odstránené z panelu.',
        'updated' => 'Konfigurácia vajca bola aktualizovaná úspešne.',
        'script_updated' => 'Inštalačný skript vajca bol aktualizovaný a bude spustený vždy pri inštalácii servera.',
        'egg_created' => 'A new egg was laid successfully. You will need to restart any running daemons to apply this new egg.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'The variable ":variable" has been deleted and will no longer be available to servers once rebuilt.',
            'variable_updated' => 'The variable ":variable" has been updated. You will need to rebuild any servers using this variable in order to apply changes.',
            'variable_created' => 'New variable has successfully been created and assigned to this egg.',
        ],
    ],
];
