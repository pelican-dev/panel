<?php

return [
    'notices' => [
        'imported' => 'As eggs e as suas variáveis de ambiente foram importadas com sucesso.',
        'updated_via_import' => 'Essa egg foi atualizada usando o arquivo fornecido.',
        'deleted' => 'A egg solicitada foi removida com sucesso do Painel.',
        'updated' => 'As configurações da egg foi atualizada com sucesso.',
        'script_updated' => 'O script de instação da egg foi atualizado e poderá ser executado quando os servidores forem instalados.',
        'egg_created' => 'Um novo egg \'foi criado com sucesso. Reinicie os daemons em execução para aplicar essa nova egg.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'A variável ":variable" foi removida com sucesso e não estará mais disponível para os servidores após a reinstalação.',
            'variable_updated' => 'A variável ":variable" foi atualizada. Reinstale os servidores utilizando essa variável para as aplicações serem alteradas.',
            'variable_created' => 'Essa variável foi criada com sucesso e vinculada com a egg.',
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
