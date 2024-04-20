<?php

return [
    'notices' => [
        'imported' => 'S\'ha importat amb èxit aquest Egg i les seves variables associades.',
        'updated_via_import' => 'Aquest Egg s\'ha actualitzat utilitzant el fitxer proporcionat.',
        'deleted' => 'S\'ha eliminat amb èxit l\'egg sol·licitat del Panell.',
        'updated' => 'La configuració de l\'Egg s\'ha actualitzat correctament.',
        'script_updated' => 'El script d\'instal·lació de l\'Egg s\'ha actualitzat i s\'executarà sempre que s\'instal·lin els servidors.',
        'egg_created' => 'S\'ha posat amb èxit un nou egg. Necessitarà reiniciar qualsevol daemon en execució per aplicar aquest nou egg.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'La variable ":variable" s\'ha eliminat i ja no estarà disponible per als servidors una vegada es reconstrueixin.',
            'variable_updated' => 'S\'ha actualitzat la variable ":variable". Hauràs de reconstruir qualsevol servidor que utilitzi aquesta variable per aplicar els canvis.',
            'variable_created' => 'S\'ha creat amb èxit una nova variable i s\'ha assignat a aquest egg.',
        ],
    ],
];
