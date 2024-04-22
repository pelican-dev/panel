<?php

return [
    'exceptions' => [
        'no_new_default_allocation' => 'Du forsøger at slette standard allokeringen for denne server, men der er ingen reserve allokeringer at bruge.',
        'marked_as_failed' => 'Denne server blev markeret som fejlet under en tidligere installationen. Nuværende status kan ikke ændres i denne tilstand.',
        'bad_variable' => 'Der opstod en valideringsfejl med :name variablen.',
        'daemon_exception' => 'Der opstod en fejl under forsøget på at kommunikere med daemonen, hvilket resulterede i en HTTP/:code responskode. Denne fejl er gemt i loggen. (request id: :request_id)',
        'default_allocation_not_found' => 'Den efterspurgte standard allokering blev ikke fundet i denne servers allokeringer.',
    ],
    'alerts' => [
        'startup_changed' => 'Startup konfigurationen for denne server er blevet opdateret. Hvis serverens egg blev ændret, vil en geninstallation starte nu.',
        'server_deleted' => 'Serveren er blevet slettet fra systemet.',
        'server_created' => 'Serveren blev oprettet på panelet. Det kan tage et par minutter at installere serveren.',
        'build_updated' => 'Serveren er blevet opdateret. Nogle ændringer kan kræve et genstart for at træde i kraft.',
        'suspension_toggled' => 'Server suspenderings status er blevet ændret til :status.',
        'rebuild_on_boot' => 'Denne server er blevet markeret til at kræve en geninstallation af Docker Container. Dette vil ske næste gang serveren startes.',
        'install_toggled' => 'Installations status for denne server er blevet ændret.',
        'server_reinstalled' => 'Denne server er blevet sat i kø til en geninstallation, der begynder nu.',
        'details_updated' => 'Server detaljerne er blevet opdateret.',
        'docker_image_updated' => 'Standard Docker container image er blevet opdateret. For at anvende dette skal du genstarte serveren.',
        'node_required' => 'Du skal have mindst en node konfigureret, før du kan tilføje en server til panelet.',
        'transfer_nodes_required' => 'Du skal have mindst to noder konfigureret for at starte en serveroverførsel.',
        'transfer_started' => 'Server flytning er blevet startet.',
        'transfer_not_viable' => 'Noden du har valgt har ikke nok disk plads eller hukommelse til at rumme denne server.',
    ],
];
