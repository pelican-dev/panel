<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => 'Proporcione la dirección de correo electrónico desde la que se deben enviar los eggs exportados por este Panel. Debe ser una dirección de correo electrónico válida.',
            'url' => 'La URL de la aplicación DEBE comenzar con https:// o http:// dependiendo de si está utilizando SSL o no. Si no incluye el esquema, sus correos electrónicos y otro contenido se vincularán a la ubicación incorrecta.',
            'timezone' => "La zona horaria debe coincidir con una de las zonas horarias admitidas por PHP. Si no está seguro, consulte https://php.net/manual/en/timezones.php.",
        ],
        'redis' => [
            'note' => 'Ha seleccionado el controlador Redis para una o más opciones, proporcione información de conexión válida a continuación. En la mayoría de los casos, puede utilizar los valores predeterminados proporcionados a menos que haya modificado su configuración.',
            'comment' => 'De forma predeterminada, una instancia de servidor Redis tiene como nombre de usuario "default" y no tiene contraseña, ya que se ejecuta localmente y es inaccesible para el mundo exterior. Si este es el caso, simplemente presione enter sin ingresar un valor.',
            'confirm' => 'Parece que ya se ha definido un :field para Redis, ¿le gustaría cambiarlo?',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => 'Se recomienda encarecidamente no utilizar "localhost" como host de la base de datos, ya que hemos visto problemas frecuentes de conexión de socket. Si desea utilizar una conexión local, debe utilizar "127.0.0.1".',
        'DB_USERNAME_note' => "El uso de la cuenta root para las conexiones MySQL no solo está muy mal visto, sino que tampoco está permitido por esta aplicación. Deberá haber creado un usuario de MySQL para este software.",
        'DB_PASSWORD_note' => 'Parece que ya tiene una contraseña de conexión MySQL definida, ¿le gustaría cambiarla?',
        'DB_error_2' => 'Sus credenciales de conexión NO se han guardado. Deberá proporcionar información de conexión válida antes de continuar.',
        'go_back' => 'Regresar e intentar de nuevo',
    ],
    'make_node' => [
        'name' => 'Ingrese un identificador corto utilizado para distinguir este nodo de otros',
        'description' => 'Ingrese una descripción para identificar el nodo',
        'scheme' => 'Ingrese https para SSL o http para una conexión sin SSL',
        'fqdn' => 'Ingrese un nombre de dominio (por ejemplo, node.example.com) para usarlo para conectarse al daemon. Solo se puede usar una dirección IP si no está utilizando SSL para este nodo',
        'public' => '¿Debería este nodo ser público? Como nota, establecer un nodo como privado denegará la capacidad de implementación automática en este nodo.',
        'behind_proxy' => '¿Su FQDN está detrás de un proxy?',
        'maintenance_mode' => '¿Debería habilitarse el modo de mantenimiento?',
        'memory' => 'Ingrese la cantidad máxima de memoria',
        'memory_overallocate' => 'Ingrese la cantidad de memoria para sobreasignar, -1 deshabilitará la verificación y 0 evitará la creación de nuevos servidores',
        'disk' => 'Ingrese la cantidad máxima de espacio en disco',
        'disk_overallocate' => 'Ingrese la cantidad de disco para sobreasignar, -1 deshabilitará la verificación y 0 evitará la creación de nuevos servidores',
        'cpu' => 'Ingrese la cantidad máxima de cpu',
        'cpu_overallocate' => 'Ingrese la cantidad de cpu para sobreasignar, -1 deshabilitará la verificación y 0 evitará la creación de nuevos servidores',
        'upload_size' => "Ingrese el tamaño máximo de carga de archivos",
        'daemonListen' => 'Ingrese el puerto de escucha del daemon',
        'daemonSFTP' => 'Ingrese el puerto de escucha SFTP del daemon',
        'daemonSFTPAlias' => 'Ingrese el alias SFTP del daemon (puede estar vacío)',
        'daemonBase' => 'Ingrese la carpeta base',
        'success' => 'Se creó correctamente un nuevo nodo con el nombre :name y tiene un ID de :id',
    ],
    'node_config' => [
        'error_not_exist' => 'El nodo seleccionado no existe.',
        'error_invalid_format' => 'Formato no válido especificado. Las opciones válidas son yaml y json.',
    ],
    'key_generate' => [
        'error_already_exist' => 'Parece que ya ha configurado una clave de cifrado de aplicación. Continuar con este proceso sobrescribirá esa clave y causará daños en los datos de cualquier dato cifrado existente. NO CONTINÚE A MENOS QUE SEPA LO QUE ESTÁ HACIENDO.',
        'understand' => 'Entiendo las consecuencias de realizar este comando y acepto toda la responsabilidad por la pérdida de datos cifrados.',
        'continue' => '¿Está seguro de que desea continuar? Cambiar la clave de cifrado de la aplicación CAUSARÁ LA PÉRDIDA DE DATOS.',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => 'No hay tareas programadas para los servidores que deben ejecutarse.',
            'error_message' => 'Se encontró un error al procesar el Horario: ',
        ],
    ],
    'upgrade' => [
        'integrity' => 'Este comando no verifica la integridad de los activos descargados. Asegúrese de confiar en la fuente de descarga antes de continuar. Si no desea descargar un archivo, indíquelo utilizando el indicador --skip-download o respondiendo "no" a la pregunta a continuación.',
        'source_url' => 'Fuente de descarga (establecida con --url=):',
        'php_version' => 'No se puede ejecutar el proceso de auto-actualización. La versión mínima requerida de PHP es 7.4.0, usted tiene',
        'skipDownload' => '¿Le gustaría descargar y descomprimir los archivos del archivo para la última versión?',
        'webserver_user' => 'Su usuario del servidor web se ha detectado como <fg=blue>[{:user}]:</> ¿es esto correcto?',
        'name_webserver' => 'Ingrese el nombre del usuario que ejecuta el proceso de su servidor web. Esto varía de un sistema a otro, pero generalmente es "www-data", "nginx" o "apache".',
        'group_webserver' => 'Su grupo de servidor web se ha detectado como <fg=blue>[{:group}]:</> ¿es esto correcto?',
        'group_webserver_question' => 'Ingrese el nombre del grupo que ejecuta el proceso de su servidor web. Normalmente, este es el mismo que su usuario.',
        'are_your_sure' => '¿Está seguro de que desea ejecutar el proceso de actualización de su Panel?',
        'terminated' => 'Proceso de actualización terminado por el usuario.',
        'success' => 'El panel se ha actualizado correctamente. Asegúrese de actualizar también cualquier instancia de Daemon',

    ],
];
