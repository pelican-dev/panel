<?php

return [
    'daemon_connection_failed' => 'Se produjo una excepción al intentar comunicarse con el daemon, lo que resultó en un código de respuesta HTTP/:code. Esta excepción ha sido registrada.',
    'node' => [
        'servers_attached' => 'Un nodo no debe tener servidores vinculados para poder ser eliminado.',
        'daemon_off_config_updated' => 'La configuración del daemon <strong>ha sido actualizada</strong>, sin embargo, se produjo un error al intentar actualizar automáticamente el archivo de configuración en el daemon. Deberá actualizar manualmente el archivo de configuración (config.yml) para que el daemon aplique estos cambios.',
    ],
    'allocations' => [
        'server_using' => 'Actualmente, un servidor está asignado a esta asignación. Una asignación solo se puede eliminar si no hay ningún servidor asignado actualmente.',
        'too_many_ports' => 'No se admite agregar más de 1000 puertos en un solo rango a la vez.',
        'invalid_mapping' => 'La asignación proporcionada para :port no era válida y no se pudo procesar.',
        'cidr_out_of_range' => 'La notación CIDR solo permite máscaras entre /25 y /32.',
        'port_out_of_range' => 'Los puertos en una asignación deben ser mayores o iguales a 1024 y menores o iguales a 65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'Un Egg con servidores activos adjuntos no se puede eliminar del Panel.',
        'invalid_copy_id' => 'El Egg seleccionado para copiar un script desde no existe o está copiando un script en sí mismo.',
        'has_children' => 'Este Egg es padre de uno o más Eggs. Elimine esos Eggs antes de eliminar este Egg.',
    ],
    'variables' => [
        'env_not_unique' => 'La variable de entorno :name debe ser única para este Egg.',
        'reserved_name' => 'La variable de entorno :name está protegida y no se puede asignar a una variable.',
        'bad_validation_rule' => 'La regla de validación ":rule" no es una regla válida para esta aplicación.',
    ],
    'importer' => [
        'json_error' => 'Se produjo un error al intentar analizar el archivo JSON: :error.',
        'file_error' => 'El archivo JSON proporcionado no era válido.',
        'invalid_json_provided' => 'El archivo JSON proporcionado no tiene un formato que pueda reconocerse.',
    ],
    'subusers' => [
        'editing_self' => 'No está permitido editar su propia cuenta de subusuario.',
        'user_is_owner' => 'No puede agregar al propietario del servidor como subusuario para este servidor.',
        'subuser_exists' => 'Ya hay un usuario con esa dirección de correo electrónico asignado como subusuario para este servidor.',
    ],
    'databases' => [
        'delete_has_databases' => 'No se puede eliminar un servidor host de base de datos que tiene bases de datos activas vinculadas.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'El tiempo de intervalo máximo para una tarea encadenada es de 15 minutos.',
    ],
    'locations' => [
        'has_nodes' => 'No se puede eliminar una ubicación que tenga nodos activos adjuntos.',
    ],
    'users' => [
        'is_self' => 'No se puede eliminar su propia cuenta de usuario.',
        'has_servers' => 'No se puede eliminar un usuario con servidores activos adjuntos a su cuenta. Elimine sus servidores antes de continuar.',
        'node_revocation_failed' => 'No se pudieron revocar las claves en <a href=":link">Nodo #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'No se pudieron encontrar nodos que satisfagan los requisitos especificados para la implementación automática.',
        'no_viable_allocations' => 'No se encontraron asignaciones que satisfagan los requisitos para la implementación automática.',
    ],
    'api' => [
        'resource_not_found' => 'El recurso solicitado no existe en este servidor.',
    ],
    'mount' => [
        'servers_attached' => 'Un montaje no debe tener servidores adjuntos para poder ser eliminado.',
    ],
    'server' => [
        'marked_as_failed' => 'Este servidor aún no ha completado su proceso de instalación, inténtelo de nuevo más tarde.',
    ],
];
