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

    'accepted' => 'Laukas :attribute turi būti patvirtintas.',
    'active_url' => 'Laukas :attribute nėra tinkama nuoroda.',
    'after' => 'Laukas :attribute turi būti data po :date.',
    'after_or_equal' => 'Laukas :attribute turi būti data po arba lygi :date.',
    'alpha' => 'Laukas :attribute gali sudaryti tik raidės.',
    'alpha_dash' => 'Laukas :attribute gali sudaryti tik raidės, skaičiai ir brūkšneliai.',
    'alpha_num' => 'Laukas :attribute gali sudaryti tik raidės ir skaičiai.',
    'array' => 'Laukas :attribute turi būti masyvas.',
    'before' => 'Laukas :attribute turi būti data prieš :date.',
    'before_or_equal' => 'Laukas :attribute turi būti data prieš arba lygi :date.',
    'between' => [
        'numeric' => 'Laukas :attribute turi būti tarp :min ir :max.',
        'file' => 'Failo dydis lauke :attribute turi būti tarp :min ir :max kilobaitų.',
        'string' => 'Simbolių skaičius lauke :attribute turi būti tarp :min ir :max.',
        'array' => 'Lauke :attribute turi būti nuo :min iki :max elementų.',
    ],

    'confirmed' => 'Lauko :attribute patvirtinimas nesutampa.',
    'date' => 'Laukas :attribute nėra tinkama data.',
    'date_format' => 'Laukas :attribute neatitinka formato :format.',
    'different' => 'Laukai :attribute ir :other turi būti skirtingi.',
    'digits' => 'Laukas :attribute turi būti sudarytas iš :digits skaitmenų.',
    'digits_between' => 'Lauko :attribute skaitmenų skaičius turi būti tarp :min ir :max.',
    'dimensions' => 'Lauko :attribute paveikslėlio matmenys yra neteisingi.',

    'email' => 'Laukas :attribute turi būti tinkamas el. pašto adresas.',

    'file' => 'Laukas :attribute turi būti failas.',
    'filled' => 'Laukas :attribute yra privalomas.',
    'image' => 'Laukas :attribute turi būti paveikslėlis.',

    'in_array' => 'Laukas :attribute neegzistuoja lauke :other.',
    'integer' => 'Laukas :attribute turi būti sveikasis skaičius.',
    'ip' => 'Laukas :attribute turi būti tinkamas IP adresas.',
    'json' => 'Laukas :attribute turi būti tinkama JSON eilutė.',
    'max' => [
        'numeric' => 'Laukas :attribute negali būti didesnis nei :max.',
        'file' => 'Failo dydis lauke :attribute negali viršyti :max kilobaitų.',
        'string' => 'Simbolių skaičius lauke :attribute negali viršyti :max.',
        'array' => 'Laukas :attribute negali turėti daugiau nei :max elementų.',
    ],
    'mimes' => 'Laukas :attribute turi būti failo tipo: :values.',
    'mimetypes' => 'Laukas :attribute turi būti failo tipo: :values.',
    'min' => [
        'numeric' => 'Laukas :attribute turi būti ne mažesnis nei :min.',
        'file' => 'Failo dydis lauke :attribute turi būti ne mažesnis nei :min kilobaitų.',
        'string' => 'Simbolių skaičius lauke :attribute turi būti ne mažesnis nei :min.',
        'array' => 'Laukas :attribute turi turėti bent :min elementų.',
    ],

    'numeric' => 'Laukas :attribute turi būti skaičius.',

    'regex' => 'Lauko :attribute formatas yra neteisingas.',

    'required_with_all' => 'Laukas :attribute yra privalomas, kai :values yra pateikti.',

    'same' => 'Laukai :attribute ir :other turi sutapti.',
    'size' => [
        'numeric' => 'Laukas :attribute turi būti :size.',
        'file' => 'Failo dydis lauke :attribute turi būti :size kilobaitų.',
        'string' => 'Simbolių skaičius lauke :attribute turi būti :size.',
        'array' => 'Laukas :attribute turi turėti :size elementų.',
    ],
    'string' => 'Laukas :attribute turi būti eilutė.',
    'timezone' => 'Laukas :attribute turi būti tinkama laiko zona.',

    'url' => 'Lauko :attribute formatas yra neteisingas.',

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
        'variable_value' => ':env kintamasis',
        'invalid_password' => 'Nurodytas slaptažodis yra neteisingas šiai paskyrai.',
    ],
];
