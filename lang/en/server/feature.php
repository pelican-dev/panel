<?php

return [
    'restart_now' => 'Server will restart now.',
    'close' => 'Close',

    'eula' => [
        'heading' => 'Minecraft EULA',
        'description' => 'By pressing "I Accept" below you are indicating your agreement to the <x-filament::link href="https://minecraft.net/eula" target="_blank">Minecraft EULA </x-filament::link>.',
        'accept' => 'I Accept',
        'accepted' => 'Minecraft EULA accepted',
        'failed' => 'Could not accept Minecraft EULA',
    ],

    'gsl_token' => [
        'heading' => 'Invalid GSL token',
        'description' => 'It seems like your Gameserver Login Token (GSL token) is invalid or has expired.',
        'submit' => 'Update GSL Token',
        'info' => 'You can either <x-filament::link href="https://steamcommunity.com/dev/managegameservers" target="_blank">generate a new one</x-filament::link> and enter it below or leave the field blank to remove it completely.',
        'updated' => 'GSL Token updated',
        'failed' => 'Could not update GSL Token',
    ],

    'java_version' => [
        'heading' => 'Unsupported Java Version',
        'description' => 'This server is currently running an unsupported version of Java and cannot be started.',
        'submit' => 'Update Docker Image',
        'select_version' => 'Please select a supported version from the list below to continue starting the server.',
        'docker_image' => 'Docker Image',
        'updated' => 'Docker image updated',
        'failed' => 'Could not update docker image',
    ],

    'pid_limit' => [
        'heading_admin' => 'Memory or process limit reached...',
        'heading_user' => 'Possible resource limit reached...',
        'description_admin' => '<p>This server has reached the maximum process or memory limit.</p><p class="mt-4">Increasing <code>container_pid_limit</code> in the wings configuration, <code>config.yml</code>, might help resolve this issue.</p><p class="mt-4"><b>Note: Wings must be restarted for the configuration file changes to take effect</b></p>',
        'description_user' => '<p>This server is attempting to use more resources than allocated. Please contact the administrator and give them the error below.</p><p class="mt-4"><code>pthread_create failed, Possibly out of memory or process/resource limits reached</code></p>',
    ],

    'steam_disk_space' => [
        'heading' => 'Out of available disk space...',
        'description_admin' => '<p>This server has run out of available disk space and cannot complete the install or update process.</p><p class="mt-4">Ensure the machine has enough disk space by typing <code class="rounded py-1 px-2">df -h</code> on the machine hosting this server. Delete files or increase the available disk space to resolve the issue.</p>',
        'description_user' => '<p>This server has run out of available disk space and cannot complete the install or update process. Please get in touch with the administrator(s) and inform them of disk space issues.</p>',
    ],
];
