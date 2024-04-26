<?php

return [
    'notices' => [
        'imported' => 'Ovos e suas variáveis associadas foram importados com sucesso.',
        'updated_via_import' => 'Este ovo foi atualizado usando o arquivo fornecido.',
        'deleted' => 'O ovo solicitado foi excluído com sucesso do painel.',
        'updated' => 'A configuração do ovo foi atualizada com sucesso.',
        'script_updated' => 'O script de instalação do ovo foi atualizado e será executado sempre que os servidores forem instalados.',
        'egg_created' => 'Um novo ovo foi criado com sucesso. Você precisará reiniciar quaisquer servidores em execução para aplicar este novo ovo.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'A variável ":variable" foi excluída e não estará mais disponível para os servidores uma vez reconstruídos.',
            'variable_updated' => 'A variável ":variable" foi atualizada. Você precisará reconstruir quaisquer servidores que usem esta variável para aplicar as mudanças.',
            'variable_created' => 'Nova variável foi criada com sucesso e atribuída a este ovo.',
        ],
    ],
    'descriptions' => [
        'name' => 'A simple, human-readable name to use as an identifier for this Egg.',
        'description' => 'A description of this Egg that will be displayed throughout the Panel as needed.',
        'uuid' => 'This is the globally unique identifier for this Egg which Wings uses as an identifier.',
        'author' => 'The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.',
        'force_outgoing_ip' => "Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.\nRequired for certain games to work properly when the Node has multiple public IP addresses.\nEnabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node.",
        'startup' => 'The default startup command that should be used for new servers using this Egg.',
        'docker_images' => 'The docker images available to servers using this egg.',
    ],
];
