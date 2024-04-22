<?php

return [
    'exceptions' => [
        'no_new_default_allocation' => 'Stai tentando di eliminare l\'allocazione predefinita per questo server, ma non c\'è allocazione di ripiego da usare.',
        'marked_as_failed' => 'Questo server è stato contrassegnato come se avesse fallito un\'installazione precedente. Lo stato attuale non può essere modificato in questo stato.',
        'bad_variable' => 'Si è verificato un errore di validazione con la variabile :name.',
        'daemon_exception' => 'C\'è stata un\'eccezione durante il tentativo di comunicare con il demone risultante in un codice di risposta HTTP/:code. Questa eccezione è stata registrata. (ID richiesta: :request_id)',
        'default_allocation_not_found' => 'L\'allocazione predefinita richiesta non è stata trovata nelle allocazioni di questo server.',
    ],
    'alerts' => [
        'startup_changed' => 'La configurazione di avvio di questo server è stata aggiornata. Se l\'uovo di questo server è stato cambiato, si verificherà una reinstallazione.',
        'server_deleted' => 'Il server è stato eliminato dal sistema.',
        'server_created' => 'Il server è stato creato con successo sul pannello. Consenti al demone di installare completamente questo server.',
        'build_updated' => 'I dettagli di compilazione per questo server sono stati aggiornati. Alcune modifiche potrebbero richiedere un riavvio per avere effetto.',
        'suspension_toggled' => 'Lo stato della sospensione del server è stato modificato in :status.',
        'rebuild_on_boot' => 'Questo server è stato contrassegnato come richiede una ricostruzione del contenitore Docker. Questo accadrà al prossimo avvio del server.',
        'install_toggled' => 'Lo stato di installazione di questo server è stato attivato.',
        'server_reinstalled' => 'Questo server è stato accodato per una reinstallazione che inizia ora.',
        'details_updated' => 'I dettagli del server sono stati aggiornati correttamente.',
        'docker_image_updated' => 'L\'immagine Docker predefinita da usare per questo server è stata modificata con successo. Per applicare questa modifica è necessario un riavvio.',
        'node_required' => 'Devi avere almeno un nodo configurato prima di poter aggiungere un server a questo pannello.',
        'transfer_nodes_required' => 'Devi avere almeno due nodi configurati prima di poter trasferire i server.',
        'transfer_started' => 'È stato avviato il trasferimento del server.',
        'transfer_not_viable' => 'Il nodo selezionato non dispone dello spazio su disco o della memoria necessaria per ospitare questo server.',
    ],
];
