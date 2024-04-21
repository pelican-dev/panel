<?php

return [
    'exceptions' => [
        'no_new_default_allocation' => 'Je probeert om een standaard toewijzing te verwijderen van de server, maar er is geen alternatieve toewijzing aanwezig.',
        'marked_as_failed' => 'De server heeft een fout gedetecteerd bij een voorgaande installatie. De huidige status kan niet worden veranderd in deze status.',
        'bad_variable' => 'Er was een validatie fout met de :name variabele.',
        'daemon_exception' => 'Er was een fout opgereden tijdens de poging om te communiceren met de daemon, met als resultaat een HTTP/:code code. Deze exceptie is opgeslagen. (aanvraag id: :request_id)',
        'default_allocation_not_found' => 'De aangevraagde standaard toewijzing is niet gevonden in de toewijzingen van deze server.',
    ],
    'alerts' => [
        'startup_changed' => 'De start configuratie van deze server is bijgewerkt. Als de egg van de server is veranderd zal een herinstallatie nu plaatsvinden.',
        'server_deleted' => 'De server is succesvol verwijderd van het systeem.',
        'server_created' => 'De server is succesvol aangemaakt op het paneel. Gelieve een paar minuten wachten op de daemon totdat de server volledig is geïnstalleerd.',
        'build_updated' => 'De build details voor deze server zijn bijgewerkt. Voor sommige wijzigingen is een herstart nodig.',
        'suspension_toggled' => 'De opschorting status van de server is veranderd naar :status.',
        'rebuild_on_boot' => 'Deze server is gemarkeerd als een opnieuw opbouwen van een Docker Container. Dit zal gebeuren bij de volgende start van de server.',
        'install_toggled' => 'De installatie status voor deze server is veranderd.',
        'server_reinstalled' => 'Deze server is in de wachtrij gezet voor een herinstallatie, deze wordt nu gestart.',
        'details_updated' => 'Server details zijn succesvol bijgewerkt.',
        'docker_image_updated' => 'De standaard Docker image die voor deze server gebruikt wordt is veranderd. Een herstart is vereist om deze wijziging toe te passen.',
        'node_required' => 'Er moet ten minste één node geconfigureerd zijn voordat u een server aan dit paneel kunt toevoegen.',
        'transfer_nodes_required' => 'U moet ten minste twee nodes geconfigureerd hebben voordat u servers kunt overzetten.',
        'transfer_started' => 'De overzetting van de server is gestart.',
        'transfer_not_viable' => 'De node die u selecteerde heeft niet de vereiste schijfruimte of geheugen beschikbaar om deze server erop te laten draaien.',
    ],
];
