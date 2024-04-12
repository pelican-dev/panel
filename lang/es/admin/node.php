<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'El FQDN o la dirección IP proporcionada no se resuelve a una dirección IP válida.',
        'fqdn_required_for_ssl' => 'Se requiere un nombre de dominio completo que se resuelva a una dirección IP pública para poder utilizar SSL en este nodo.',
    ],
    'notices' => [
        'allocations_added' => 'Se han añadido correctamente las asignaciones a este nodo.',
        'node_deleted' => 'El nodo se ha eliminado correctamente del panel.',
        'node_created' => 'Se ha creado correctamente un nuevo nodo. Puedes configurar automáticamente el daemon en esta máquina visitando la pestaña \'Configuración\'. <strong>Antes de poder añadir cualquier servidor, primero debes asignar al menos una dirección IP y puerto.</strong>',
        'node_updated' => 'Se ha actualizado la información del nodo. Si se cambiaron ajustes del daemon, necesitarás reiniciarlo para que los cambios surtan efecto.',
        'unallocated_deleted' => 'Se han eliminado todos los puertos no asignados para <code>:ip</code>.',
    ],
];
