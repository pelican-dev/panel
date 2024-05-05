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
    'descriptions' => [
        'name' => 'A simple, human-readable name to use as an identifier for this Egg.',
        'description' => 'A description of this Egg that will be displayed throughout the Panel as needed.',
        'uuid' => 'This is the globally unique identifier for this Egg which Wings uses as an identifier.',
        'author' => 'The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.',
        'force_outgoing_ip' => "Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.\nRequired for certain games to work properly when the Node has multiple public IP addresses.\nEnabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node.",
        'startup' => 'The default startup command that should be used for new servers using this Egg.',
        'docker_images' => 'The docker images available to servers using this egg.',
    ],
];
