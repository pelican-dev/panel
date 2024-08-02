<?php

return [
    'exceptions' => [
        'user_has_servers' => 'Cannot delete a user with active servers attached to their account. Please delete their servers before continuing.',
        'user_is_self' => 'Cannot delete your own user account.',
    ],
    'notices' => [
        'account_created' => 'Account has been created successfully.',
        'account_updated' => 'Account has been successfully updated.',
    ],
    'last_admin' => [
        'hint' => 'This is the last root administrator!',
        'helper_text' => 'You must have at least one root administrator in your system.',
    ],
    'language' => [
        'helper_text1' => 'Your language (:state) has not been translated yet!\nBut never fear, you can help fix that by',
        'helper_text2' => 'contributing directly here',
    ],
];
