<?php

return [
    'exceptions' => [
        'no_new_default_allocation' => 'Вы пытаетесь удалить основной порт сервера без дополнительных портов.',
        'marked_as_failed' => 'This server was marked as having failed a previous installation. Current status cannot be toggled in this state.',
        'bad_variable' => 'Произошла ошибка проверки переменной :name.',
        'daemon_exception' => 'При попытке связи с узлом произошла ошибка HTTP/:code. Информация была передана администрации. (идентификатор запроса: :request_id)',
        'default_allocation_not_found' => 'The requested default allocation was not found in this server\'s allocations.',
    ],
    'alerts' => [
        'startup_changed' => 'Конфигурация запуска для этого сервера была обновлена. Если яйцо этого сервера было изменено, то сейчас произойдет переустановка.',
        'server_deleted' => 'Сервер успешно удален из системы.',
        'server_created' => 'Сервер успешно создан. Пожалуйста, дайте демону несколько минут, чтобы закончить установку сервера.',
        'build_updated' => 'The build details for this server have been updated. Some changes may require a restart to take effect.',
        'suspension_toggled' => 'Server suspension status has been changed to :status.',
        'rebuild_on_boot' => 'This server has been marked as requiring a Docker Container rebuild. This will happen the next time the server is started.',
        'install_toggled' => 'The installation status for this server has been toggled.',
        'server_reinstalled' => 'Этот сервер был поставлен в очередь на переустановку.',
        'details_updated' => 'Информация о сервере успешно обновлена.',
        'docker_image_updated' => 'Successfully changed the default Docker image to use for this server. A reboot is required to apply this change.',
        'node_required' => 'You must have at least one node configured before you can add a server to this panel.',
        'transfer_nodes_required' => 'You must have at least two nodes configured before you can transfer servers.',
        'transfer_started' => 'Миграция сервера начата.',
        'transfer_not_viable' => 'The node you selected does not have the required disk space or memory available to accommodate this server.',
    ],
];
