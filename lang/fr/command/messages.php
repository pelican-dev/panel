<?php

return [
    'user' => [
        'search_users' => 'Entrez un nom d\'utilisateur, un ID ou une adresse e-mail',
        'select_search_user' => 'ID de l\'utilisateur à supprimer (entrez \'0\' pour rechercher)',
        'deleted' => 'L\'utilisateur a bien été supprimé du panel',
        'confirm_delete' => 'Êtes-vous sûr de vouloir supprimer cet utilisateur du panel ?',
        'no_users_found' => 'Aucun utilisateur n\'a été trouvé pour le terme de recherche fourni.',
        'multiple_found' => 'Plusieurs comptes ont été trouvés pour l\'utilisateur fourni, impossible de supprimer un utilisateur à cause de l\'option --no-Interaction.',
        'ask_admin' => 'Cet utilisateur est-il un administrateur ?',
        'ask_email' => 'Adresse e-mail',
        'ask_username' => 'Nom d\'utilisateur',
        'ask_name_first' => 'Prénom',
        'ask_name_last' => 'Nom',
        'ask_password' => 'Mot de passe',
        'ask_password_tip' => 'Si vous souhaitez créer un compte avec un mot de passe aléatoire envoyé à l\'utilisateur, ré-exécutez cette commande (CTRL+C) et passez le paramètre `--no-password`.',
        'ask_password_help' => 'Les mots de passe doivent comporter au moins 8 caractères et contenir au moins une lettre majuscule et un chiffre.',
        '2fa_help_text' => [
            'Cette commande désactivera la double authentification pour le compte d\'un utilisateur s\'il est activé. Ceci ne devrait être utilisé comme une commande de récupération de compte que si l\'utilisateur est bloqué sur son compte.',
            'If this is not what you wanted to do, press CTRL+C to exit this process.',
        ],
        '2fa_disabled' => '2-Factor authentication has been disabled for :email.',
    ],
    'schedule' => [
        'output_line' => 'Dispatching job for first task in `:schedule` (:hash).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Deleting service backup file :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Rebuild request for ":name" (#:id) on node ":node" failed with error: :message',
        'reinstall' => [
            'failed' => 'Reinstall request for ":name" (#:id) on node ":node" failed with error: :message',
            'confirm' => 'You are about to reinstall against a group of servers. Do you wish to continue?',
        ],
        'power' => [
            'confirm' => 'You are about to perform a :action against :count servers. Do you wish to continue?',
            'action_failed' => 'Power action request for ":name" (#:id) on node ":node" failed with error: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'Hôte SMTP (ex: smtp.gmail.com)',
            'ask_smtp_port' => 'Port SMTP',
            'ask_smtp_username' => 'Nom d\'utilisateur SMTP',
            'ask_smtp_password' => 'Mot de passe SMTP',
            'ask_mailgun_domain' => 'Domaine Mailgun',
            'ask_mailgun_endpoint' => 'Url Mailgun',
            'ask_mailgun_secret' => 'Secret Mailgun',
            'ask_mandrill_secret' => 'Secret Mandrill',
            'ask_postmark_username' => 'Clé API Postmark',
            'ask_driver' => 'Quel pilote doit être utilisé pour envoyer des emails?',
            'ask_mail_from' => 'Les courriels de l\'adresse e-mail devraient provenir de',
            'ask_mail_name' => 'Nom à partir duquel les e-mails devraient apparaître',
            'ask_encryption' => 'Méthode de chiffrement à utiliser',
        ],
    ],
];
