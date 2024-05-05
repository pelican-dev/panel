<?php

return [
    'exceptions' => [
        'user_has_servers' => 'Não é possível excluir um usuário com servidores ativos vinculados à sua conta. Por favor, exclua os servidores antes de continuar.',
        'user_is_self' => 'Não é possível excluir sua própria conta de usuário.',
    ],
    'notices' => [
        'account_created' => 'A conta foi criada com sucesso.',
        'account_updated' => 'A conta foi atualizada com sucesso.',
    ],
    'last_admin' => [
        'hint' => 'This is the last root administrator!',
        'helper_text' => 'You must have at least one root administrator in your system.',
    ],
    'root_admin' => 'Administrator (Root)',
    'language' => [
        'helper_text1' => 'Your language (:state) has not been translated yet!\nBut never fear, you can help fix that by',
        'helper_text2' => 'contributing directly here',
    ],
];
