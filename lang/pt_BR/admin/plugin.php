<?php

return [
    'nav_title' => 'Extensões',
    'model_label' => 'Extensão',
    'model_label_plural' => 'Extensões',

    'name' => 'Nome',
    'update_available' => 'Uma atualização está disponível para essa extensão',
    'author' => 'Autor',
    'version' => 'Versão',
    'category' => 'Categoria',
    'status' => 'Estado',
    'visit_website' => 'Visitar Site',
    'settings' => 'Configurações',
    'install' => 'Instalar',
    'uninstall' => 'Desinstalar',
    'update' => 'Atualizar',
    'enable' => 'Ativar',
    'disable' => 'Desativar',
    'import_from_file' => 'Importar de Arquivo',
    'import_from_url' => 'Importar de URL',
    'no_plugins' => 'Sem Extensões',
    'all' => 'Todos',
    'change_load_order' => 'Alterar ordem de carregamento',
    'apply_load_order' => 'Aplicar ordem de carregamento',

    'enable_theme_modal' => [
        'heading' => 'Tema já habilitado',
        'description' => 'Você já tem um tema habilitado. Habilitar vários temas pode resultar em erros visuais. Deseja continuar?',
    ],

    'status_enum' => [
        'not_installed' => 'Não Instalado',
        'disabled' => 'Desativado',
        'enabled' => 'Ativado',
        'errored' => 'Erro',
        'incompatible' => 'Incompatível',
    ],

    'category_enum' => [
        'plugin' => 'Extensão',
        'theme' => 'Tema',
        'language' => 'Pacote de Idioma',
    ],

    'notifications' => [
        'goto_plugins' => 'Ir para Plugins',
        'background_info' => 'Este processo pode levar alguns segundos. Você será notificado assim que terminar.',

        'install_started' => 'A instalação do plugin foi iniciada em segundo plano',
        'installed' => 'Extensão instalada',
        'install_error' => 'Não foi possível instalar a extensão',

        'uninstall_started' => 'A desinstalação do plugin foi iniciada em segundo plano',
        'uninstalled' => 'Extensão desinstalada',
        'uninstall_error' => 'Não foi possível desinstalar a extensão',

        'update_started' => 'Atualização de plugin iniciada em segundo plano',
        'updated' => 'Extensão atualizada',
        'update_error' => 'Não foi possível atualizar a extensão',

        'enabled' => 'Extensão ativada',
        'disabled' => 'Extensão desativada',
        'deleted' => 'Extensão deletada',

        'imported' => 'Extensão importada',
        'import_exists' => 'Uma extensão com esse ID já existe',
        'import_failed' => 'Não foi possível importar extensão',
    ],
];
