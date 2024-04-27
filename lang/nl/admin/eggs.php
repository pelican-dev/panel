<?php

return [
    'notices' => [
        'imported' => 'Het importeren van deze egg en de bijbehorende variabelen is geslaagd.',
        'updated_via_import' => 'Deze egg is bijgewerkt met behulp van het opgegeven bestand.',
        'deleted' => 'De aangevraagde egg is met succes uit het paneel verwijderd.',
        'updated' => 'Egg configuratie is met succes bijgewerkt.',
        'script_updated' => 'Egg install script is bijgewerkt en wordt uitgevoerd wanneer er servers worden geïnstalleerd.',
        'egg_created' => 'Een nieuw egg is met succes toegevoegd. U moet elke lopende daemon opnieuw opstarten om deze nieuwe egg toe te passen.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'De variabele ":variable" is verwijderd en zal niet meer beschikbaar zijn voor servers nadat deze opnieuw zijn opgebouwd.',
            'variable_updated' => 'De variabele ":variable" is bijgewerkt. Je moet elke server opnieuw opbouwen met deze variabele om wijzigingen toe te passen.',
            'variable_created' => 'Er is een nieuwe variabele aangemaakt en toegewezen aan deze egg.',
        ],
    ],
    'descriptions' => [
        'name' => 'Een eenvoudige, menselijk leesbare naam om te gebruiken als identificator voor dit Egg.',
        'description' => 'A description of this Egg that will be displayed throughout the Panel as needed.',
        'uuid' => 'This is the globally unique identifier for this Egg which Wings uses as an identifier.',
        'author' => 'The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.',
        'force_outgoing_ip' => "Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.\nRequired for certain games to work properly when the Node has multiple public IP addresses.\nEnabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node.",
        'startup' => 'The default startup command that should be used for new servers using this Egg.',
        'docker_images' => 'The docker images available to servers using this egg.',
    ],
];
