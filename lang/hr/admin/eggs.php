<?php

return [
    'notices' => [
        'imported' => 'Uspješno ste uvezli ovaj Egg i njegove varijable.',
        'updated_via_import' => 'Ovaj Egg je ažuriran sa tom datotekom.',
        'deleted' => 'Uspješno ste obrisali taj Egg sa panel-a.',
        'updated' => 'Konfiguracija Egg-a je uspješno ažurirana.',
        'script_updated' => 'Egg skripta za instaliranje je ažurirana i pokreniti će se kada se serveri instaliraju.',
        'egg_created' => 'Uspješno ste napravili Egg. Morat ćete restartati sve pokrenute daemone da bih primjenilo ovaj novi Egg.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'Varijabla ":variable" je uspješno obrisana i više neće biti dostupna za servera nakon obnovljenja.',
            'variable_updated' => 'Varijabla ":variable" je ažurirana. Morat ćete obnoviti sve servere koji koriste ovu varijablu kako biste primijenili promjene.',
            'variable_created' => 'Nova varijabla je uspješno napravljena i dodana ovom Egg-u.',
        ],
    ],
];
