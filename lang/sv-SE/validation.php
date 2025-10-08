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

    'accepted' => ':attribute måste accepteras.',
    'active_url' => ':attribute är inte en giltig webbadress.',
    'after' => ':attribute måste vara ett datum efter :date.',
    'after_or_equal' => ':attribute måste vara ett datum efter eller lika med :date.',
    'alpha' => ':attribute får enbart innehålla bokstäver.',
    'alpha_dash' => ':attribute får endast innehålla bokstäver, siffror och bindestreck.',
    'alpha_num' => ':attribute får endast innehålla bokstäver, siffror och bindestreck.',
    'array' => ':attribute måste vara en lista.',
    'before' => ':attribute måste vara ett datum före :date.',
    'before_or_equal' => ':attribute måste vara ett datum före eller lika med :date.',
    'between' => [
        'numeric' => ':attribute måste vara mellan :min och :max.',
        'file' => ':attribute måste vara mellan :min och :max kilobytes.',
        'string' => ':attribute måste vara mellan :min och :max tecken.',
        'array' => ':attribute måste vara mellan :min och :max föremål.',
    ],

    'confirmed' => ':attribute bekräftelsen matchar inte.',
    'date' => ':attribute är inte ett giltigt datum.',
    'date_format' => ':attribute matchar inte formatet :format.',
    'different' => ':attribute och :other måste vara olika.',
    'digits' => ':attribute måste vara :digits siffror.',
    'digits_between' => ':attribute måste vara mellan :min och :max siffror.',
    'dimensions' => ':attribute har ogiltiga bilddimensioner.',

    'email' => ':attribute måste vara en giltig e-postadress.',

    'file' => ':attribute måste vara en fil.',
    'filled' => ':attribute fältet är obligatoriskt.',
    'image' => ':attribute måste vara en bild.',

    'in_array' => 'Fältet :attribute existerar inte i :other.',
    'integer' => ':attribute måste vara en siffra.',
    'ip' => ':attribute måste vara en giltig IP-adress.',
    'json' => ':attribute måste vara en giltig JSON-sträng.',
    'max' => [
        'numeric' => ':attribute får inte vara större än :max.',
        'file' => ':attribute får inte vara större än :max kilobytes.',
        'string' => ':attribute får inte vara större än :max tecken.',
        'array' => ':attribute får inte ha mer än :max artiklar.',
    ],
    'mimes' => ':attribute måste vara filtyp: :values',
    'mimetypes' => ':attribute måste vara filtyp: :values',
    'min' => [
        'numeric' => ':attribute måste vara minst :min.',
        'file' => ':attribute måste vara minst :min kilobytes.',
        'string' => ':attribute måste vara längre än :min tecken.',
        'array' => ':attribute måste innehålla minst :min artiklar.',
    ],

    'numeric' => ':attribute måste vara en siffra.',

    'regex' => ':attribute formatet är ogiltigt.',

    'required_with_all' => ':attribute fältet är obligatoriskt när :values är angivet.',

    'same' => ':attribute och :other måste stämma överens.',
    'size' => [
        'numeric' => ':attribute måste vara :size.',
        'file' => ':attribute måste vara :size kilobytes.',
        'string' => ':attribute måste vara :size tecken.',
        'array' => ':attribute måste innehålla :size artiklar.',
    ],
    'string' => ':attribute måste vara en sträng.',
    'timezone' => ':attribute måste vara en giltig tidszon.',

    'url' => ':attribute formatet är ogiltigt.',

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
        'variable_value' => ':env variabel',
        'invalid_password' => 'Lösenordet som angavs var ogiltigt för detta konto.',
    ],
];
