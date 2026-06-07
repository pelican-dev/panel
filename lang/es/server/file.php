<?php

return [
    'title' => 'Archivos',
    'name' => 'Nombre',
    'size' => 'Tamaño',
    'modified_at' => 'Modificado en',
    'actions' => [
        'open' => 'Abrir',
        'download' => 'Descargar',
        'copy' => [
            'title' => 'Copiar',
            'notification' => 'Archivo copiado',
        ],
        'upload' => [
            'title' => 'Subir',
            'from_files' => 'Subir archivos',
            'from_url' => 'Subir desde una URL',
            'url' => 'URL',
            'drop_files' => 'Suelta los archivos para subirlos',
            'success' => 'Archivos subidos correctamente',
            'failed' => 'Error al subir los archivos',
            'header' => 'Subiendo archivos',
            'error' => 'Se ha producido un error durante la subida',
        ],
        'rename' => [
            'title' => 'Renombrar',
            'file_name' => 'Nombre del archivo',
            'notification' => 'Archivo renombrado',
        ],
        'move' => [
            'title' => 'Mover',
            'directory' => 'Directorio',
            'directory_hint' => 'Introduce el nuevo directorio, relativo al directorio actual.',
            'new_location' => 'Nueva ubicación',
            'new_location_hint' => 'Introduce la ubicación de este archivo o carpeta, relativa al directorio actual.',
            'notification' => 'Archivo movido',
            'bulk_notification' => ':count archivos fueron movidos a :directory',
        ],
        'permissions' => [
            'title' => 'Permisos',
            'read' => 'Lectura',
            'write' => 'Escritura',
            'execute' => 'Ejecutar',
            'owner' => 'Propietario',
            'group' => 'Grupo',
            'public' => 'Público',
            'notification' => 'Permisos cambiados a :mode',
        ],
        'archive' => [
            'title' => 'Comprimir',
            'archive_name' => 'Nombre del archivo',
            'notification' => 'Archivo creado',
            'extension' => 'Extensión',
        ],
        'unarchive' => [
            'title' => 'Descomprimir',
            'notification' => 'Descompresión completada',
        ],
        'new_file' => [
            'title' => 'Nuevo archivo',
            'file_name' => 'Nuevo nombre del archivo',
            'syntax' => 'Resaltado de sintaxis',
            'create' => 'Crear',
        ],
        'new_folder' => [
            'title' => 'Nueva carpeta',
            'folder_name' => 'Nombre de la nueva carpeta',
        ],
        'nested_search' => [
            'title' => 'Búsqueda anidada',
            'search_term' => 'Buscar término',
            'search_term_placeholder' => 'Introduce un término de búsqueda, p. ej. *.txt',
            'search' => 'Buscar',
            'search_for_term' => 'Buscar :term',
        ],
        'delete' => [
            'notification' => 'Archivo eliminado',
            'bulk_notification' => ':count archivos fueron eliminados',
        ],
        'edit' => [
            'title' => 'Editando: :file',
            'save_close' => 'Guardar y cerrar',
            'save' => 'Guardar',
            'cancel' => 'Cancelar',
            'notification' => 'Archivo guardado',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '¡<code>:name</code> es demasiado largo!',
            'body' => 'El máximo es :max',
        ],
        'file_not_found' => [
            'title' => '¡<code>:name</code> no encontrado!',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> es un directorio',
        ],
        'file_already_exists' => [
            'title' => '¡<code>:name</code> ya existe!',
        ],
        'files_node_error' => [
            'title' => '¡No se pudieron cargar los archivos!',
        ],
        'pelicanignore' => [
            'title' => '¡Estás editando un archivo <code>.pelicanignore</code>!',
            'body' => 'Cualquier archivo o directorio que se liste aquí se excluirá de las copias de seguridad. Se admiten comodines usando un asterisco (<code>*</code>).<br>Puedes negar una regla anterior anteponiendo un signo de exclamación (<code>!</code>).',
        ],
    ],
];
