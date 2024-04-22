<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'Der angegebene FQDN oder die IP-Adresse wird nicht mit einer gültigen IP-Adresse aufgelöst.',
        'fqdn_required_for_ssl' => 'Um SSL für diese Node nutzen zu können, ist ein FQDN erforderlich, welcher eine öffentliche IP besitzt.',
    ],
    'notices' => [
        'allocations_added' => 'Zuweisungen wurden erfolgreich zu dieser Node hinzugefügt.',
        'node_deleted' => 'Node wurde erfolgreich aus dem Panel entfernt.',
        'node_created' => 'Neue Node erfolgreich erstellt. Du kannst den Daemon auf dieser Maschine automatisch konfigurieren, indem du die Registerkarte "Konfiguration" aufrufst. <strong>Bevor du Server hinzufügen kannst, musst du zuerst mindestens eine IP-Adresse und einen Port zuweisen.</strong>',
        'node_updated' => 'Nodeinformationen wurden aktualisiert. Wenn irgendwelche Daemon-Einstellungen geändert wurden, musst du den Node neu starten, damit diese Änderungen wirksam werden.',
        'unallocated_deleted' => 'Alle nicht zugewiesenen Ports für <code>:ip</code> gelöscht.',
    ],
];
