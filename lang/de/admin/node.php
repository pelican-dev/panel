<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'Die angegebene FQDN oder IP-Adresse wird nicht mit einer gültigen IP-Adresse aufgelöst.',
        'fqdn_required_for_ssl' => 'Um SSL für diese Node nutzen zu können, ist ein FQDN erforderlich,welcher eine öffentliche IP besitzt.',
    ],
    'notices' => [
        'allocations_added' => 'Allokationen wurden erfolgreich zu diesem Node hinzugefügt.',
        'node_deleted' => 'Node wurde erfolgreich aus dem Panel entfernt.',
        'node_created' => 'Neuer Node erfolgreich erstellt. Sie können den Daemon auf dieser Maschine automatisch konfigurieren, indem Sie die Registerkarte "Konfiguration" aufrufen. <strong>Bevor du Server hinzufügen kannst, musst du zuerst mindestens eine IP-Adresse und einen Port zuweisen.</strong>',
        'node_updated' => 'Nodeinformationen wurden aktualisiert. Wenn irgendwelche Daemon-Einstellungen geändert wurden, musst du den Node neu starten, damit diese Änderungen wirksam werden.',
        'unallocated_deleted' => 'Alle nicht zugewiesenen Ports für <code>:ip</code> gelöscht.',
    ],
];
