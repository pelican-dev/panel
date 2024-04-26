<?php

return [
    'exceptions' => [
        'user_has_servers' => 'Δεν είναι δυνατή η διαγραφή ενός χρήστη με ενεργούς διακομιστές συνδεδεμένους στον λογαριασμό του. Παρακαλούμε διαγράψτε τους διακομιστές του πριν συνεχίσετε.',
        'user_is_self' => 'Δεν μπορείτε να διαγράψετε το δικό σας λογαριασμό χρήστη.',
    ],
    'notices' => [
        'account_created' => 'Ο λογαριασμός δημιουργηθήκε με επιτυχία.',
        'account_updated' => 'Ο λογαριασμός ενημερώθηκε με επιτυχία.',
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
