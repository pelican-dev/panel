<?php

return [
    'nav_title' => 'Roles',
    'model_label' => 'Role',
    'model_label_plural' => 'Roles',
    'no_roles' => 'No Roles',
    'name' => 'Role Name',
    'permissions' => 'Permissions',
    'in_use' => 'In Use',
    'all' => 'All',
    'root_admin' => 'The :role has all permissions.',
    'root_admin_delete' => 'Can\'t delete Root Admin',
    'users' => 'Users',
    'nodes' => 'Nodes',
    'nodes_hint' => 'Leave empty to allow access to all nodes.',

    // Permission section headings (RolePermissionModels enum + special permission groups)
    'models' => [
        'apiKey' => 'API Key',
        'allocation' => 'Allocation',
        'databaseHost' => 'Database Host',
        'database' => 'Database',
        'egg' => 'Egg',
        'mount' => 'Mount',
        'node' => 'Node',
        'role' => 'Role',
        'server' => 'Server',
        'user' => 'User',
        'webhook' => 'Webhook',
        'settings' => 'Settings',
        'health' => 'Health',
        'activityLog' => 'Activity Log',
        'panelLog' => 'Panel Log',
        'plugin' => 'Plugin',
    ],

    // Permission checkbox labels (RolePermissionPrefixes enum + model-specific/special prefixes)
    'permissions_list' => [
        'viewList' => 'View List',
        'view' => 'View',
        'create' => 'Create',
        'update' => 'Update',
        'delete' => 'Delete',
        'import' => 'Import',
        'export' => 'Export',
        'seeIps' => 'See IPs',
    ],
];
