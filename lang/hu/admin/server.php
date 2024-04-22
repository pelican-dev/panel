<?php

return [
    'exceptions' => [
        'no_new_default_allocation' => 'Megpróbáltad törölni az allokációt, de nincs másik alapértelmezett allokáció hozzáadva a szerverhez.',
        'marked_as_failed' => 'Ezt a kiszolgálót úgy jelölték meg, hogy egy korábbi telepítés sikertelen volt. Az állapot követés nem kapcsolható be ebben az állapotban!',
        'bad_variable' => 'Érvényesítési hiba történt a :name: váltózóval!',
        'daemon_exception' => 'There was an exception while attempting to communicate with the daemon resulting in a HTTP/:code response code. This exception has been logged. (request id: :request_id)',
        'default_allocation_not_found' => 'The requested default allocation was not found in this server\'s allocations.',
    ],
    'alerts' => [
        'startup_changed' => 'The startup configuration for this server has been updated. If this server\'s egg was changed a reinstall will be occurring now.',
        'server_deleted' => 'Szerver sikeresen eltávolítva.',
        'server_created' => 'Szerver sikeresen létrehozva. Várj néhány percet, amíg a daemon teljesen feltelepíti a szervert.',
        'build_updated' => 'The build details for this server have been updated. Some changes may require a restart to take effect.',
        'suspension_toggled' => 'Server suspension status has been changed to :status.',
        'rebuild_on_boot' => 'This server has been marked as requiring a Docker Container rebuild. This will happen the next time the server is started.',
        'install_toggled' => 'The installation status for this server has been toggled.',
        'server_reinstalled' => 'This server has been queued for a reinstallation beginning now.',
        'details_updated' => 'Server details have been successfully updated.',
        'docker_image_updated' => 'Successfully changed the default Docker image to use for this server. A reboot is required to apply this change.',
        'node_required' => 'Legalább egy node-ot konfigurálni kell szerverek hozzáadásához.',
        'transfer_nodes_required' => 'Legalább két node-nak kell lennie szerverek költöztetéséhez.',
        'transfer_started' => 'Szerver költöztetés elindítva.',
        'transfer_not_viable' => 'The node you selected does not have the required disk space or memory available to accommodate this server.',
    ],
];
