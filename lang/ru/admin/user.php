<?php

return [
    'exceptions' => [
        'user_has_servers' => 'Невозможно удалить пользователя с активными серверами, привязанными к его учетной записи. Пожалуйста, удалите его серверы, прежде чем продолжить.',
        'user_is_self' => 'Невозможно удалить свою учетную запись.',
    ],
    'notices' => [
        'account_created' => 'Учетная запись успешно создана!',
        'account_updated' => 'Аккаунт был успешно изменен.',
    ],
    'last_admin' => [
        'hint' => 'Это последний root администратор!',
        'helper_text' => 'В вашей системе должен быть хотя бы один root администратор.',
    ],
    'root_admin' => 'Администратор (Root)',
    'language' => [
        'helper_text1' => 'Your language (:state) has not been translated yet!\nBut never fear, you can help fix that by',
        'helper_text2' => 'contributing directly here',
    ],
];
