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
                'header' => 'Reinstall Server',
                'button' => 'Reinstall',
                'action_heading' => 'Are you sure you want to reinstall the server?',
                'action_desc' => 'Some files may be deleted or modified during this process, please back up your data before continuing.',
                'action_confirm' => 'Yes, Reinstall',
                'started' => 'Server Reinstall started',
                'action_detail_line1' => 'Reinstalling your server will stop it, and then re-run the installation script that initially set it up.',
                'action_detail_line2' => 'Some files may be deleted or modified during this process, please back up your data before continuing.',
                'failed' => 'Server Reinstall failed',
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
    'consolestatus' => [
        'start' => 'Start',
        'restart' => 'Restart',
        'stop' => 'Stop',
        'kill' =>'Kill',
        'kill_help' => 'This can result in data corruption and/or data loss!',
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