<?php

return [
    'exceptions' => [
        'no_new_default_allocation' => 'Pokúšate sa odstrániť predvolené pridelenie pre tento server, ale nie je možné použiť žiadne záložné pridelenie.',
        'marked_as_failed' => 'Tento server bol označený ako neúspešný pri predchádzajúcej inštalácii. V tomto stave nie je možné prepnúť aktuálny stav.',
        'bad_variable' => 'Pri overení premennej :name sa vyskytla chyba.',
        'daemon_exception' => 'Pri pokuse o komunikáciu s daémonom sa vyskytla chyba, čo malo za následok kód odpovede HTTP/:code. Táto chyba bola zaznamenaná. (id žiadosti: :request_id)',
        'default_allocation_not_found' => 'Požadované predvolené pridelenie sa nenašlo v pridelení tohto servera.',
    ],
    'alerts' => [
        'startup_changed' => 'Konfigurácia spúšťania pre tento server bola aktualizovaná. Ak sa vajíčko tohto servera zmenilo, dôjde k preinštalovaniu.',
        'server_deleted' => 'Server bol úspešne odstránený zo systému.',
        'server_created' => 'Server bol úspešne vytvorený na paneli. Nechajte daémonovi niekoľko minút na úplnú inštaláciu tohto servera.',
        'build_updated' => 'Podrobnosti zostavy pre tento server boli aktualizované. Niektoré zmeny môžu vyžadovať reštart, aby sa prejavili.',
        'suspension_toggled' => 'Server suspension status has been changed to :status.',
        'rebuild_on_boot' => 'This server has been marked as requiring a Docker Container rebuild. This will happen the next time the server is started.',
        'install_toggled' => 'The installation status for this server has been toggled.',
        'server_reinstalled' => 'This server has been queued for a reinstallation beginning now.',
        'details_updated' => 'Server details have been successfully updated.',
        'docker_image_updated' => 'Successfully changed the default Docker image to use for this server. A reboot is required to apply this change.',
        'node_required' => 'You must have at least one node configured before you can add a server to this panel.',
        'transfer_nodes_required' => 'You must have at least two nodes configured before you can transfer servers.',
        'transfer_started' => 'Server transfer has been started.',
        'transfer_not_viable' => 'The node you selected does not have the required disk space or memory available to accommodate this server.',
    ],
];
