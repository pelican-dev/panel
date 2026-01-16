<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => 'Indique la dirección de correo electrónico desde la cual deberían enviarse los huevos exportados por este Panel. Debe ser una dirección de email válida.',
            'url' => 'La URL de la aplicación DEBE comenzar con https:// o http:// dependiendo de si estás utilizando SSL o no. Si no incluyes el esquema, tus correos electrónicos y otros contenidos se vincularán al lugar incorrecto.',
            'timezone' => 'La zona horaria debe coincidir con una de las zonas horarias soportadas por PHP\\. Si no estás seguro, por favor consulta https://php.net/manual/en/timezones.php.',
        ],
        'redis' => [
            'note' => 'Has seleccionado el controlador Redis para una o más opciones, por favor proporciona información de conexión válida a continuación. En la mayoría de los casos, puedes utilizar los valores predeterminados proporcionados a menos que hayas modificado tu configuración.',
            'comment' => 'Por defecto, una instancia de servidor Redis no tiene contraseña, ya que se ejecuta localmente y es inaccesible desde el exterior. Si este es el caso, simplemente presiona Enter sin ingresar algún valor.',
            'confirm' => 'Parece que un campo :field ya está definido para Redis, ¿quieres cambiarlo?',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => 'Se recomienda encarecidamente no utilizar "localhost" como el hospedaje de tu base de datos, ya que hemos observado problemas frecuentes de conexión de socket. Si deseas utilizar una conexión local, deberías estar utilizando "127.0.0.1".',
        'DB_USERNAME_note' => 'El uso de la cuenta "root", o raíz, para conexiones MySQL no sólo está muy mal visto, sino que además no está permitido por esta aplicación. Necesitarás haber creado un usuario MySQL para este software.',
        'DB_PASSWORD_note' => 'Parece que ya tienes definida una contraseña de conexión MySQL, ¿te gustaría cambiarla?',
        'DB_error_2' => 'Tus credenciales de conexión NO han sido guardadas. Necesitarás proporcionar información de conexión válida antes de continuar.',
        'go_back' => 'Regresa e inténtalo de nuevo',
    ],
    'make_node' => [
        'name' => 'Introduce un identificador corto utilizado para distinguir este nodo de otros.',
        'description' => 'Introduce una descripción para identificar el nodo.',
        'scheme' => 'Por favor, ingresa https para SSL o http para una conexión sin SSL.',
        'fqdn' => 'Introduce un nombre de dominio (por ejemplo, nodo.ejemplo.com) que se utilizará para conectarse al daemon. Una dirección IP solo puede ser utilizada si no estás usando SSL para este nodo.',
        'public' => '¿Debería este nodo ser público? Como nota, al establecer un nodo como privado, estarás denegando la capacidad de desplegar automáticamente en este nodo.',
        'behind_proxy' => '¿Está tu FQDN detrás de un proxy?',
        'maintenance_mode' => '¿Debe activarse el modo de mantenimientos?',
        'memory' => 'Introduce la cantidad máxima de memoria',
        'memory_overallocate' => 'Introduce la cantidad de memoria para sobreasignar, -1 deshabilitará la verificación y 0 impedirá la creación de nuevos servidores.',
        'disk' => 'Introduce la cantidad máxima de espacio en disco',
        'disk_overallocate' => 'Introduce la cantidad de almacenamiento para sobreasignar, -1 deshabilitará la verificación y 0 impedirá la creación de nuevos servidores.',
        'cpu' => 'Introduce la cantidad máxima de cpu',
        'cpu_overallocate' => 'Introduce la cantidad de cpu para sobreasignar, -1 deshabilitará la verificación y 0 impedirá la creación de nuevos servidores.',
        'upload_size' => "'Introduce el tamaño máximo de archivo para cargar",
        'daemonListen' => 'Introduce el puerto de escucha del demonio',
        'daemonConnect' => 'Ingresa el puerto de conexión del servicio (puede ser el mismo que el puerto de escucha)',
        'daemonSFTP' => 'Introduce el puerto de escucha del demonio SFTP',
        'daemonSFTPAlias' => 'Introduzca el nombre del demonio SFTP(puede estar vacío)',
        'daemonBase' => 'Introduzca la carpeta raíz',
        'success' => 'Se ha creado correctamente un nuevo nodo con el nombre :name y tiene un id de :id',
    ],
    'node_config' => [
        'error_not_exist' => 'El nodo seleccionado no existe.',
        'error_invalid_format' => 'Formato especificado no válido. Las opciones válidas son yaml y json.',
    ],
    'key_generate' => [
        'error_already_exist' => 'Parece que ya has configurado una clave de cifrado de la aplicación. Continuar con este proceso sobrescribirá esa clave y causará corrupción de los datos para cualquier dato cifrado existente. NO CONTINÚES A MENOS QUE SEPAS LO QUE ESTÁS HACIENDO.',
        'understand' => 'Entiendo las consecuencias de realizar este comando y acepto toda la responsabilidad por la pérdida de datos cifrados.',
        'continue' => '¿Estás seguro de que deseas continuar? Cambiar la clave de cifrado de la aplicación CAUSARÁ PÉRDIDA DE DATOS.',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => 'No hay tareas programadas para los servidores que necesiten ser ejecutadas.',
            'error_message' => 'Se encontró un error al procesar el Horario: ',
        ],
    ],
];
