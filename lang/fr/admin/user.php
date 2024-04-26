<?php

return [
    'exceptions' => [
        'user_has_servers' => 'Impossible de supprimer un utilisateur avec des serveurs actifs attachés à son compte. Veuillez supprimer ses serveurs avant de continuer.',
        'user_is_self' => 'Vous ne pouvez pas supprimer votre propre compte.',
    ],
    'notices' => [
        'account_created' => 'Compte créé avec succès.',
        'account_updated' => 'Compte mis à jour avec succès.',
    ],
    'last_admin' => [
        'hint' => 'This is the last root administrator!',
        'helper_text' => 'You must have at least one root administrator in your system.',
    ],
    'root_admin' => 'Administrateur (Root)',
    'language' => [
        'helper_text1' => 'Votre langue (:state) n\'a pas encore été traduite !\nMais pas d\'inquiétude, vous pouvez aider à corriger ça en',
        'helper_text2' => 'contribuant directement ici',
    ],
];
