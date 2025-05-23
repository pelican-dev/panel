<?php

return [
    'appsettings' => [
        'redis' => [ 
            'title_field' => 'Redis Host',
            'user_field' => 'Redis User',
            'password_field' => 'Redis Password',
            'port_field' => 'Redis Port',
        ],
    ],
    'console' => [
        'overview' => [
            'name' => 'Name',
            'status' => 'Status',
            'address' => 'Address',
            'cpu' => 'CPU',
            'memory' => 'Memory',
            'disk' => 'Disk',
            'diskusage' => [
                'unavailable' => 'Unavailable',
            ],
        ],
        'actions' => [
            'start' => 'Start',
            'restart' => 'Restart',
            'stop' => 'Stop',
            'kill' => 'Kill',
        ],
        'settings' => [
            'basic' => [
                'heading' => 'Server Information',
                'title' => 'Information',
                'server_name' => 'Server Name',
                'server_descriptions' => 'Server Description',
                'server_uuid' => 'Server UUID',
                'server_id' => 'Server ID',
                'limits' => [
                    'title' => 'Limits',
                    'cpu_prefix' => 'CPU',
                    'memory_prefix' => 'Memory',
                    'disk_prefix' => 'Disk Space',
                    'backups_prefix' => 'Backups',
                    'databases_prefix' => 'Databases',
                ],
            ],
            'node' => [
                'heading' => 'Node Information',
                'node_name' => 'Node Name',
                'sftp_header' => 'SFTP Information',
                'sftp_connection' => 'Connection',
                'sftp_calltoaction' => 'Connect to SFTP',
                'sftp_username' => 'Username',
                'sftp_password' => 'Password',
            ],
            'reinstall' => [
                'heading' => 'Reinstall Server',
                'reinstall_btn' => 'Reinstall',
            ],
            'tag_unlimited' => 'Unlimited',
            'tag_nobackups' => 'No Backups',
            'tag_nodatabases' => 'No Databases',
            'tag_noadditionalallocations' => 'No Additional Allocations',
        ],
    ],
    'containerstatus' => [
        'created' => 'Created',
        'starting' => 'Starting',
        'running' => 'Running',
        'restarting' => 'Restarting',
        'exited' => 'Exited',
        'paused' => 'paused',
        'dead' => 'Dead',
        'removing' => 'Removing',
        'stopping' => 'Stopping',
        'offline' => 'Offline',
    ],
    'backupstatus' => [
        'InProgress' => 'In Progress',
        'Successful' => 'Successful',
        'Failed' => 'Failed',        
    ],
    'server' => [
        'widgets' => [
            'headings' => [
                'CPU' => 'CPU',
                'Memory' => 'Memory',
                'Network1' => 'Network - ↓',
                'Network2' => ' - ↑',
            ],
            'tablecolumn' => [
                'cpu' => 'cpu',
                'timestamp' => 'timestamp',
            ],
        ],
    ],
];