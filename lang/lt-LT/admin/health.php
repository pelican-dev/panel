<?php

return [
    'title' => 'Sveikata',
    'results_refreshed' => 'Sveikatos patikrinimo rezultatai atnaujinti',
    'checked' => 'Paskutinį kartą tikrinta :time',
    'refresh' => 'Atnaujinti',
    'results' => [
        'cache' => [
            'label' => 'Talpykla',
            'ok' => 'Gera',
            'failed_retrieve' => 'Nepavyko nustatyti arba gauti programos talpyklos vertės.',
            'failed' => 'Su programos talpykla iškilo problema: :error',
        ],
        'database' => [
            'label' => 'Duomenų bazė',
            'ok' => 'Gera',
            'failed' => 'Nepavyko prisijungti prie duomenų bazės: :error',
        ],
        'debugmode' => [
            'label' => 'Tvarkymo režimas',
            'ok' => 'Tvarkymo režimas išjungtas',
            'failed' => 'Tikėtasi, kad tvarkymo režimas bus : expected ,bet iš tikrųjų buvo :actual',
        ],
        'environment' => [
            'label' => 'Aplinka',
            'ok' => 'Sėkmingai nustatyta į :actual',
            'failed' => 'Aplinka nustatyta į :actual ,tikėtasi :expected',
        ],
        'nodeversions' => [
            'label' => '„node“ versija',
            'ok' => 'Visi „node“ yra naujausios versijos',
            'failed' => ':outdaated/:all „node“ yra pasenę',
            'no_nodes_created' => 'Nesukurta jokių „node“',
            'no_nodes' => 'Nėra nei vieno „node“',
            'all_up_to_date' => 'Visi naujausi',
            'outdated' => ':outdated/:all pasenę',
        ],
        'panelversion' => [
            'label' => 'Valdymo punkto versija',
            'ok' => 'Jūsų valdymo punktas turi naujausią versiją',
            'failed' => 'Įdiegta versija yra :currentVersion ,bet naujausia yra :latestVersion',
            'up_to_date' => 'Naujausia',
            'outdated' => 'Pasenęs(-usi)',
        ],
        'schedule' => [
            'label' => 'Tvarkaraštis',
            'ok' => 'Geras',
            'failed_last_ran' => 'Paskutinį kartą tvarkaraštis buvo paleistas daugiau nei prieš :time minutes',
            'failed_not_ran' => 'Tvarkaraštis dar nebuvo paleistas.',
        ],
        'useddiskspace' => [
            'label' => 'Naudojama disko talpa',
        ],
    ],
    'checks' => [
        'successful' => 'Sėkminga',
        'failed' => 'Nepavyko',
    ],
];
