<?php

return [
    'title' => 'Arquivos',
    'name' => 'Nome',
    'size' => 'Tamanho',
    'modified_at' => 'Modificado em',
    'actions' => [
        'open' => 'Abrir',
        'download' => 'Fazer Download',
        'copy' => [
            'title' => 'Copiar',
            'notification' => 'Arquivo Copiado',
        ],
        'upload' => [
            'title' => 'Fazer Upload',
            'from_files' => 'Enviar Arquivos',
            'from_url' => 'Upload da URL',
            'url' => 'URL',
            'drop_files' => 'Arraste os arquivos para enviar',
            'success' => 'Arquivos enviados com sucesso',
            'failed' => 'Falha ao enviar arquivos',
            'header' => 'Enviando arquivos',
            'error' => 'Ocorreu um erro ao enviar',
        ],
        'rename' => [
            'title' => 'Renomear',
            'file_name' => 'Nome do Arquivo',
            'notification' => 'Arquivo Renomeado',
        ],
        'move' => [
            'title' => 'Mover',
            'directory' => 'Diretório',
            'directory_hint' => 'Informe o novo diretório, relativo ao diretório atual',
            'new_location' => 'Novo Local',
            'new_location_hint' => 'Informe o local deste arquivo ou pasta, relativo ao diretório atual.',
            'notification' => 'Arquivo Movido',
            'bulk_notification' => ':count arquivos foram movidos para :directory',
        ],
        'permissions' => [
            'title' => 'Permissões',
            'read' => 'Leitura',
            'write' => 'Escrita',
            'execute' => 'Executar',
            'owner' => 'Proprietário',
            'group' => 'Grupo',
            'public' => 'Público',
            'notification' => 'Permissões alteradas para :mode',
        ],
        'archive' => [
            'title' => 'Arquivar',
            'archive_name' => 'Nome do Arquivo',
            'notification' => 'Arquivo Criado',
            'extension' => 'Extensão',
        ],
        'unarchive' => [
            'title' => 'Desarquivar',
            'notification' => 'Desarquivamento Concluído',
        ],
        'new_file' => [
            'title' => 'Novo arquivo',
            'file_name' => 'Nome do novo arquivo',
            'syntax' => 'Destaque de Sintaxe',
            'create' => 'Criar',
        ],
        'new_folder' => [
            'title' => 'Nova pasta',
            'folder_name' => 'Nome da nova pasta',
        ],
        'nested_search' => [
            'title' => 'Nested Search',
            'search_term' => 'Search term',
            'search_term_placeholder' => 'Enter a search term, ex. *.txt',
            'search' => 'Search',
            'search_for_term' => 'Search :term',
        ],
        'delete' => [
            'notification' => 'Arquivo Excluído',
            'bulk_notification' => ':count arquivos foram excluídos',
        ],
        'edit' => [
            'title' => 'Editando: :file',
            'save_close' => 'Salvar e Fechar',
            'save' => 'Salvar',
            'cancel' => 'Cancelar',
            'notification' => 'Arquivo Salvo',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>:name</code> é muito grande!',
            'body' => 'O máximo é :max',
        ],
        'file_not_found' => [
            'title' => '<code>:name</code> não encontrado!',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> é um diretório',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> já existe!',
        ],
        'files_node_error' => [
            'title' => 'Não foi possível carregar os arquivos!',
        ],
        'pelicanignore' => [
            'title' => 'Você está editando um arquivo <code>.pelicanignore</code>!',
            'body' => 'Quaisquer arquivos ou diretórios listados aqui serão excluídos dos backups. Curingas são suportados ao usar um asterisco (<code>*</code>).<br>Você pode negar uma regra prévia adicionando um ponto de exclamação (<code>!</code>).',
        ],
    ],
];
