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
        'name' => 'Un nombre simple y legible por humanos para usar como identificador para este Egg.',
        'description' => 'Una descripción de este Egg que se mostrará en el Panel según sea necesario.',
        'uuid' => 'Esto es el identificador único global para este Egg que Wings utiliza como identificador.',
        'author' => 'El autor de esta versión del Egg. Subir una nueva configuración del Egg de un autor diferente cambiará esto.',
        'force_outgoing_ip' => 'Fuerza que todo el tráfico de red saliente tenga su IP de origen traducida a la IP de asignación primaria del servidor. Es necesario para que algunos juegos funcionen correctamente cuando el nodo tiene múltiples direcciones IP públicas. Habilitar esta opción desactivará la red interna para cualquier servidor que utilice este Egg, lo que hará que no puedan acceder internamente a otros servidores en el mismo nodo.',
        'startup' => 'El comando de inicio predeterminado que debe ser usado para servidores nuevos usando este Egg.',
        'docker_images' => 'Las imágenes de docker disponibles para los servidores usando este Egg.',
    ],
];
