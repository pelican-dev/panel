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
];
