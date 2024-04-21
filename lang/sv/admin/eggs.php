<?php

return [
    'notices' => [
        'imported' => 'Lyckades importera detta ägg och dess associerade variabler.',
        'updated_via_import' => 'Detta ägg har uppdaterats med den fil som tillhandahållits.',
        'deleted' => 'Lyckades radera det begärda ägget från panelen.',
        'updated' => 'Äggkonfigurationen har uppdaterats framgångsrikt.',
        'script_updated' => 'Egg install script has been updated and will run whenever servers are installed.',
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
