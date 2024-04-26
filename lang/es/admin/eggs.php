<?php

return [
    'notices' => [
        'imported' => 'El Egg y sus variables asociadas se importaron correctamente.',
        'updated_via_import' => 'Este Egg se ha actualizado utilizando el archivo proporcionado.',
        'deleted' => 'Se eliminó correctamente el Egg solicitado del Panel.',
        'updated' => 'La configuración del Egg se ha actualizado correctamente.',
        'script_updated' => 'El script de instalación del Egg se ha actualizado y se ejecutará cada vez que se instalen servidores.',
        'egg_created' => 'Se ha creado un nuevo Egg correctamente. Deberás reiniciar cualquier daemon en ejecución para aplicar este nuevo Egg.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'La variable ":variable" se ha eliminado y ya no estará disponible para los servidores una vez reconstruidos.',
            'variable_updated' => 'La variable ":variable" se ha actualizado. Deberás reconstruir cualquier servidor que utilice esta variable para aplicar los cambios.',
            'variable_created' => 'Se ha creado correctamente una nueva variable y se ha asignado a este Egg.',
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
