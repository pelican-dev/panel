<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => 'Zadejte e-mailovou adresu, ze které by měly být vajíčka exportovaná tímto panelem. Toto by mělo být platná e-mailová adresa.',
            'url' => 'URL aplikace MUSÍ začít s https:// nebo http:// v závislosti na tom, zda používáte SSL nebo ne. Pokud nezahrnete do schématu vaše e-maily a jiný obsah bude odkazovat na nesprávné umístění.',
            'timezone' => 'Časové pásmo by mělo odpovídat jednomu z podporovaných časových pásem PHP. Pokud si nejste jisti, prosím na https://php.net/manual/en/timezones.php.',
        ],
        'redis' => [
            'note' => 'Vybrali jste ovladač Redis pro jednu nebo více možností, poskytněte prosím platné informace o připojení níže. Ve většině případů můžete použít výchozí hodnoty, pokud jste nezměnili nastavení.',
            'comment' => 'Ve výchozím nastavení má instance Redis serveru výchozí uživatelské jméno a žádné heslo, protože běží lokálně a je nepřístupné vnějšímu světu. Pokud tomu tak je, stiskněte klávesu Enter bez zadání hodnoty.',
            'confirm' => 'Zdá se, že :field je již definovaný pro Redis, chcete ho změnit?',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => 'Je důrazně doporučeno nepoužívat "localhost" jako hostitele databáze, protože jsme viděli časté problémy s připojením socketu. Pokud chcete použít místní připojení, měli byste použít "127.0.0.1".',
        'DB_USERNAME_note' => 'Použití kořenového účtu pro MySQL spojení není pouze velmi rozblednuté, ale tato aplikace také nepovoluje. Pro tento software budete muset vytvořit MySQL uživatele.',
        'DB_PASSWORD_note' => 'Zdá se, že heslo pro připojení k MySQL je již definováno, chcete jej změnit?',
        'DB_error_2' => 'Vaše přihlašovací údaje k připojení NEJSOU uloženy. Před pokračováním budete muset poskytnout platné informace o připojení.',
        'go_back' => 'Vraťte se zpět a zkuste to znovu',
    ],
    'make_node' => [
        'name' => 'Zadejte krátký identifikátor používaný k rozlišení tohoto uzlu od ostatních',
        'description' => 'Zadejte popis pro identifikaci uzlu',
        'scheme' => 'Prosím, zadejte https for SSL nebo http pro jiné než ssl připojení',
        'fqdn' => 'Zadejte název domény (např. node.example.com) pro připojení k Démonu. IP adresa může být použita pouze v případě, že pro tento uzel nepoužíváte SSL',
        'public' => 'Měl by být tento uzel veřejný? Pro poznámku, nastavením uzlu soukromému vám bude odepřena možnost automatické nasazení do tohoto uzlu.',
        'behind_proxy' => 'Je tvá FQDN za proxy?',
        'maintenance_mode' => 'Měl by být režim údržby aktivován?',
        'memory' => 'Zadejte maximální množství paměti',
        'memory_overallocate' => 'Zadejte množství paměti k překročení alokace, -1 vypne kontrolu a 0 zabrání vytvoření nových serverů',
        'disk' => 'Zadejte maximální množství diskového místa',
        'disk_overallocate' => 'Zadejte množství disku na překrytí přiděleného množství, -1 zakáže kontrolu a 0 zabrání vytváření nového serveru',
        'cpu' => 'Zadejte maximální množství pro Cpu',
        'cpu_overallocate' => 'Zadejte množství Cpu k překročení přiděleného množství, -1 vypne kontrolu a 0 zabrání vytvoření nového serveru',
        'upload_size' => 'Zadejte maximální velikost nahrávání',
        'daemonListen' => 'Vložte port poslechu daemon',
        'daemonConnect' => 'Zadejte port pro připojení démona (může být stejný jako port pro naslouchání)',
        'daemonSFTP' => 'Zadejte port pro poslech SFTP',
        'daemonSFTPAlias' => 'Zadejte alias Démon SFTP (může být prázdný)',
        'daemonBase' => 'Zadej základní složku',
        'success' => 'Nový uzel s názvem :name byl úspěšně vytvořen a obsahuje Id :id',
    ],
    'node_config' => [
        'error_not_exist' => 'Vybraný uzel neexistuje.',
        'error_invalid_format' => 'Byl zadán neplatný formát. Platné možnosti jsou yaml a json.',
    ],
    'key_generate' => [
        'error_already_exist' => 'Zdá se, že již jste nakonfigurovali šifrovací klíč aplikace. Pokračujte v tomto procesu přepsáním klíče a způsobte poškození dat u všech existujících šifrovaných dat. NEPOTVRZUJTE NEPOVINNÉ, KE KTERÉ JSOU JSOU DOTČENÉ.',
        'understand' => 'Chápu důsledky provedení tohoto příkazu a přebírám veškerou odpovědnost za ztrátu šifrovaných dat.',
        'continue' => 'Opravdu chcete pokračovat? Změna šifrovacího klíče aplikace ZPŮSOBÍ ZTRÁTU DAT.',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => 'Neexistují žádné naplánované úkoly pro servery, které je třeba spustit.',
            'error_message' => 'Došlo k chybě při zpracování plánu: ',
        ],
    ],
];
