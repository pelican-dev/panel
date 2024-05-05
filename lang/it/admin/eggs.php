<?php

return [
    'notices' => [
        'imported' => 'Importato con successo questo Uovo e le sue variabili associate.',
        'updated_via_import' => 'Questo Uovo è stato aggiornato usando il file fornito.',
        'deleted' => 'Hai eliminato con successo l\'uovo richiesto dal pannello.',
        'updated' => 'Configurazione dell\'Uovo aggiornata con successo.',
        'script_updated' => 'Lo script d\'installazione di Uovo è stato aggiornato e verrà eseguito ogni volta che i server vengono installati.',
        'egg_created' => 'Un nuovo uovo è stato creato con successo. Dovrai riavviare qualsiasi demone in esecuzione per applicare questo nuovo uovo.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'La variabile ":variable" è stata eliminata e non sarà più disponibile ai server una volta ricostruita.',
            'variable_updated' => 'La variabile ":variable" è stata aggiornata. È necessario ricostruire tutti i server usando questa variabile per applicare le modifiche.',
            'variable_created' => 'La nuova variabile è stata creata e assegnata a questo uovo.',
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
