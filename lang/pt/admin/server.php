<?php

return [
    'exceptions' => [
        'no_new_default_allocation' => 'Você não pode remover uma alocação de um servidor sem possuir outras alocações.',
        'marked_as_failed' => 'Este servidor falhou durante uma instalação anterior. O status atual não pode ser alterado nesse estado.',
        'bad_variable' => 'Ocorreu um erro de validação na varável :name.',
        'daemon_exception' => 'Ocorreu um erro ao tentar se comunicar com a máquina, resultando um status code HTTP/:code. Esse problema foi gerado com request id :request_id',
        'default_allocation_not_found' => 'A alocação padrão não foi encontrado nas alocações dos servidores.',
    ],
    'alerts' => [
        'startup_changed' => 'A configuração de inicialização foi atualizada com sucesso. Caso a egg deste servidor tenha sido atualizado, reinstale o servidor.',
        'server_deleted' => 'Este servidor foi removido do sistema com sucesso.',
        'server_created' => 'O servidor foi criado com sucesso no painel. Aguarde alguns minutos a máquina terminar de instalar o servidor.',
        'build_updated' => 'As configurações de build foram atualizadas. Será necessário reiniciar o servidor para aplicar algumas alterações.',
        'suspension_toggled' => 'O status de suspensão do servidor foi alterada para :status',
        'rebuild_on_boot' => 'Esse servidor foi alterado para reinstalar o Docker Container. Isso será aplicado na próxima vez que o servidor for iniciado.',
        'install_toggled' => 'O status de instalação foi ativado para esse servidor.',
        'server_reinstalled' => 'Este servidor foi colocado na fila para reinstalação a partir de agora.',
        'details_updated' => 'Os detalhes do servidor foram atualizadas.',
        'docker_image_updated' => 'A imagem do docker foi atualizada para ser utilizado neste servidor com sucesso. É necessário reiniciar o servidor para aplicar as alterações.',
        'node_required' => 'É necessário de um node configurado antes de adicionar esse servidor ao painel.',
        'transfer_nodes_required' => 'É necessário de pelo menos dois nodes para transferir os servidores.',
        'transfer_started' => 'A transferência de servidores começou.',
        'transfer_not_viable' => 'O node selecionado não há espaço em disco ou memória suficiente para acomodar esse servidor.',
    ],
];
