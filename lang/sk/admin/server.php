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
        'suspension_toggled' => 'Stav pozastavenia servera sa zmenil na :status.',
        'rebuild_on_boot' => 'Tento server bol označený ako server vyžadujúci prebudovanie kontajnera Docker. Stane sa tak pri ďalšom spustení servera.',
        'install_toggled' => 'Stav inštalácie pre tento server bol prepnutý.',
        'server_reinstalled' => 'Tento server bol zaradený do poradia na preinštalovanie, ktoré sa teraz začína.',
        'details_updated' => 'Podrobnosti servera boli úspešne aktualizované.',
        'docker_image_updated' => 'Úspešne sa zmenil predvolený Docker image na použitie pre tento server. Na uplatnenie tejto zmeny je potrebný reštart.',
        'node_required' => 'Pred pridaním servera na tento panel musíte mať nakonfigurovaný aspoň jednu node.',
        'transfer_nodes_required' => 'Pred prenosom serverov musíte mať nakonfigurované aspoň dve nody.',
        'transfer_started' => 'Prenos servera bol spustený.',
        'transfer_not_viable' => 'Nodu, ktorú ste vybrali, nemá k dispozícii požadovaný diskový priestor alebo pamäť na umiestnenie tohto servera.',
    ],
];
