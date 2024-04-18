<?php

return [
    'exceptions' => [
        'no_new_default_allocation' => 'Estás intentando eliminar la asignación predeterminada para este servidor pero no hay una asignación de respaldo para usar.',
        'marked_as_failed' => 'Este servidor fue marcado como que ha fallado en una instalación anterior. El estado actual no se puede cambiar en este estado.',
        'bad_variable' => 'Hubo un error de validación con la variable :name.',
        'daemon_exception' => 'Hubo una excepción al intentar comunicarse con el daemon que resultó en un código de respuesta HTTP/:code. Esta excepción ha sido registrada. (ID de solicitud: :request_id)',
        'default_allocation_not_found' => 'La asignación predeterminada solicitada no se encontró en las asignaciones de este servidor.',
    ],
    'alerts' => [
        'startup_changed' => 'La configuración de inicio de este servidor se ha actualizado. Si se cambió el huevo de este servidor, se iniciará una reinstalación ahora.',
        'server_deleted' => 'El servidor se ha eliminado correctamente del sistema.',
        'server_created' => 'El servidor se ha creado correctamente en el panel. Por favor, permite al daemon unos minutos para instalar completamente este servidor.',
        'build_updated' => 'Los detalles de construcción para este servidor se han actualizado. Algunos cambios pueden requerir un reinicio para surtir efecto.',
        'suspension_toggled' => 'El estado de suspensión del servidor se ha cambiado a :status.',
        'rebuild_on_boot' => 'Este servidor se ha marcado como que requiere una reconstrucción del contenedor Docker. Esto ocurrirá la próxima vez que se inicie el servidor.',
        'install_toggled' => 'El estado de instalación para este servidor se ha cambiado.',
        'server_reinstalled' => 'Este servidor ha sido encolado para una reinstalación que comienza ahora.',
        'details_updated' => 'Los detalles del servidor se han actualizado correctamente.',
        'docker_image_updated' => 'Se cambió con éxito la imagen Docker predeterminada para usar en este servidor. Se requiere un reinicio para aplicar este cambio.',
        'node_required' => 'Debes tener al menos un nodo configurado antes de poder añadir un servidor a este panel.',
        'transfer_nodes_required' => 'Debes tener al menos dos nodos configurados antes de poder transferir servidores.',
        'transfer_started' => 'La transferencia del servidor se ha iniciado.',
        'transfer_not_viable' => 'El nodo que seleccionaste no tiene el espacio en disco o la memoria disponible requerida para acomodar este servidor.',
    ],
];
