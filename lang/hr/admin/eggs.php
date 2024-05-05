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
