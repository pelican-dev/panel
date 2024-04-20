<?php

return [
    'exceptions' => [
        'no_new_default_allocation' => 'Pokoušíte se odstranit výchozí alokaci pro tento server, ale není k dispozici žádná záložní alokace.',
        'marked_as_failed' => 'Tento server byl označen jako neúspěšný předchozí instalace. Aktuální stav nelze v tomto stavu přepnout.',
        'bad_variable' => 'Došlo k chybě ověření proměnné :name.',
        'daemon_exception' => 'Při pokusu o komunikaci s daemonem došlo k výjimce, která vedla k HTTP/:code kódu odpovědi. Tato výjimka byla zaznamenána. (požadavek id: :request_id)',
        'default_allocation_not_found' => 'Požadovaná výchozí alokace nebyla nalezena v alokaci tohoto serveru.',
    ],
    'alerts' => [
        'startup_changed' => 'Konfigurace spouštění pro tento server byla aktualizována. Pokud bylo vejce tohoto serveru změněno, přeinstalování se nyní bude opakovat.',
        'server_deleted' => 'Server byl ze systému úspěšně odstraněn.',
        'server_created' => 'Server byl úspěšně vytvořen v panelu. Povolte prosím démonovi několik minut pro úplnou instalaci tohoto serveru.',
        'build_updated' => 'Detaily sestavení tohoto serveru byly aktualizovány. Některé změny mohou vyžadovat restartování.',
        'suspension_toggled' => 'Stav pozastavení serveru byl změněn na :status.',
        'rebuild_on_boot' => 'Tento server byl označen jako server vyžadující přesestavení kontejneru Docker. To se stane při příštím spuštění serveru.',
        'install_toggled' => 'Stav instalace pro tento server byl přepnut.',
        'server_reinstalled' => 'Tento server byl zařazen do fronty pro reinstalaci, která je nyní zahájena.',
        'details_updated' => 'Podrobnosti o serveru byly úspěšně aktualizovány.',
        'docker_image_updated' => 'Úspěšně změněn výchozí obraz Dockeru, který má být použit pro tento server. Pro tuto změnu je nutný restart.',
        'node_required' => 'Před přidáním serveru do tohoto panelu musíte mít nakonfigurován alespoň jeden uzel.',
        'transfer_nodes_required' => 'Před přenosem serverů musíte mít nakonfigurovány alespoň dva uzly.',
        'transfer_started' => 'Přenos serveru byl zahájen.',
        'transfer_not_viable' => 'Vybraný uzel nemá k dispozici požadovaný prostor na disku nebo paměť pro uložení tohoto serveru.',
    ],
];
