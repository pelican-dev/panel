<?php

return [
    'title' => 'Health',
    'results_refreshed' => 'Health check results updated',
    'checked' => 'Checked results from :time',
    'results' => [
        'cache' => [
            'label' => 'Cache',
            'ok' => 'Ok',
            'failed' => 'Cache is not working correctly',
        ],
        'database' => [
            'label' => 'Database',
            'ok' => 'Ok',
            'failed' => 'Could not connect to the database: :error',
        ],
        'debugmode' => [
            'label' => 'Debug Mode',
            'ok' => 'Debug mode is disabled',
            'failed' => 'Debug mode is enabled',
        ],
        'environment' => [
            'label' => 'Environment',
            'ok' => 'Ok, Set to :actual',
            'failed' => 'Environment is set to :actual , Expected :expected',
        ],
        'nodeversions' => [
            'label' => 'Node Versions',
            'ok' => 'Nodes are up-to-date',
            'failed' => ':outdated/:all Nodes are outdated',
        ],
        'panelversion' => [
            'label' => 'Panel Version',
            'ok' => 'Panel is up-to-date',
            'failed' => 'Installed version is :currentVersion but latest is :latestVersion',
        ],
        'schedule' => [
            'label' => 'Schedule',
            'ok' => 'Ok',
            'failed' => 'The schedule did not run yet',
            'last_ran' => 'The last run of the schedule was more than :time minutes ago',
        ],
        'useddiskspace' => [
            'label' => 'Disk Space',
        ],
    ],
    'checks' => [
        'successful' => 'Successful',
        'failed' => 'Failed',
    ],
];
