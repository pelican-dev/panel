<?php

return [
    'nav_title' => 'Mounts',
    'model_label' => 'Mount',
    'model_label_plural' => 'Mounts',
    'name' => 'Name',
    'name_help' => 'Unique name used to separate this mount from another.',
    'source' => 'Source',
    'source_help' => 'File path on the host system to mount to a container.',
    'target' => 'Target',
    'target_help' => 'Where the mount will be accessible inside a container.',
    'read_only' => 'Read Only?',
    'read_only_help' => 'Is the mount read only inside the container?',
    'description' => 'Description',
    'description_help' => 'A longer description for this Mount',
    'no_mounts' => 'No Mounts',
    'eggs' => 'Eggs',
    'nodes' => 'Nodes',
    'toggles' => [
        'writable' => 'Writable',
        'read_only' => 'Read Only',
    ],
    'table' => [
        'name' => 'Name',
        'all_eggs' => 'All Eggs',
        'all_nodes' => 'All Nodes',
        'read_only' => 'Read Only',
    ],
];
