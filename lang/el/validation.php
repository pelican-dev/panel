<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Το :attribute πρέπει να γίνει αποδεκτό.',
    'active_url' => 'Το :attribute δεν είναι μια έγκυρη διεύθυνση.',
    'after' => 'Το :attribute πρέπει να είναι μια ημερομηνία μετά τις :date.',
    'after_or_equal' => 'Tο :attribute πρέπει να είναι μια ημερομηνία μετά ή ίδια με :date.',
    'alpha' => 'Το :attribute μπορεί να περιέχει μόνο γράμματα.',
    'alpha_dash' => 'Το :attribute μπορεί να περιέχει μόνο γράμματα, αριθμούς και παύλες.',
    'alpha_num' => 'Το :attribute μπορεί να περιέχει μόνο γράμματα και αριθμούς.',
    'array' => 'Το :attribute πρέπει να είναι πίνακας.',
    'before' => 'Tο :attribute πρέπει να είναι μια ημερομηνία πριν από :date.',
    'before_or_equal' => 'Το :attribute πρέπει να είναι μια ημερομηνία πριν ή ίση με :date.',
    'between' => [
        'numeric' => 'Tο :attribute πρέπει να είναι ανάμεσα σε :min και :max.',
        'file' => 'Το :attribute πρέπει να είναι μεταξύ :min και :max kilobytes.',
        'string' => 'Το :attribute πρέπει να είναι μεταξύ :min και :max χαρακτήρες.',
        'array' => 'Tο :attribute πρέπει να είναι ανάμεσα σε :min και :max αντικείμενα.',
    ],

    'confirmed' => 'Η επιβεβαίωση του :attribute δεν ταιριάζει.',
    'date' => 'Το :attribute δεν είναι μια έγκυρη ημερομηνία.',
    'date_format' => 'Tο :attribute δεν ταιριάζει με την μορφή :format.',
    'different' => 'Το :attribute και :other πρέπει να είναι διαφορετικά.',
    'digits' => 'Το :attribute πρέπει να είναι :digits ψηφία.',
    'digits_between' => 'Το :attribute πρέπει να είναι μεταξύ των ψηφίων :min και :max.',
    'dimensions' => 'To :attribute έχει μη έγκυρες διαστάσεις εικόνας.',

    'email' => 'Tο :attribute πρέπει να είναι μια έγκυρη διεύθυνση ηλεκτρονικού ταχυδρομείου.',

    'file' => 'Tο :attribute πρέπει να είναι αρχείο.',
    'filled' => 'Το πεδίο :attribute είναι υποχρεωτικό.',
    'image' => 'Το :attribute πρέπει να είναι εικόνα.',

    'in_array' => 'Το πεδίο :attribute δεν υπάρχει στο :other.',
    'integer' => 'Tο :attribute πρέπει να είναι ακέραιος αριθμός.',
    'ip' => 'Το πεδίο :attribute πρέπει να είναι μία έγκυρη διεύθυνση IP.',
    'json' => 'Το :attribute πρέπει να είναι έγκυρο JSON string.',
    'max' => [
        'numeric' => 'Το :attribute δεν μπορεί να είναι μεγαλύτερο από :max.',
        'file' => 'To :attribute δεν μπορεί να είναι μεγαλύτερο από :max kilobytes.',
        'string' => 'Το :attribute δεν μπορεί να είναι μεγαλύτερο από :max χαρακτήρες.',
        'array' => 'Tο :attribute δεν μπορεί να έχει περισσότερα από :max αντικείμενα.',
    ],
    'mimes' => 'Το :attribute πρέπει να είναι ένα αρχείου τύπου: :values.',
    'mimetypes' => 'Το :attribute πρέπει να είναι ένα αρχείο τύπου: :values.',
    'min' => [
        'numeric' => 'Το :attribute πρέπει να είναι τουλάχιστον: min.',
        'file' => 'Tο :attribute πρέπει να είναι το λιγότερο :min kilobytes.',
        'string' => 'Το :attribute πρέπει να είναι τουλάχιστον :min χαρακτήρες.',
        'array' => 'To :attribute πρέπει να έχει τουλάχιστον :min αντικείμενα.',
    ],

    'numeric' => 'To :attribute πρέπει να είναι αριθμός.',

    'regex' => 'Το :attribute έχει μη έγκυρη μορφή.',

    'required_with_all' => 'Tο :attribute πεδίο είναι υποχρεωτικό όταν υπάρχουν :values.',

    'same' => 'Το :attribute και :other πρέπει να ταιριάζουν.',
    'size' => [
        'numeric' => 'Το :attribute πρέπει να είναι :size.',
        'file' => 'Το :attribute πρέπει να έχει μέγεθος :size kilobytes.',
        'string' => 'Το :attribute πρέπει να έχει μέγεθος :size χαρακτήρων.',
        'array' => 'Το :attribute πρέπει να περιέχει μέγεθος :size αντικειμένων.',
    ],
    'string' => 'Το :attribute πρέπει να είναι string.',
    'timezone' => 'Το :attribute πρέπει να είναι μία έγκυρη ζώνη ώρας.',

    'url' => 'Το :attribute έχει μη έγκυρη μορφή.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

    // Internal validation logic for Panel
    'internal' => [
        'variable_value' => ':env μεταβλητή',
        'invalid_password' => 'Ο κωδικός πρόσβασης που δόθηκε δεν ήταν έγκυρος για αυτόν το λογαριασμό.',
    ],
];
