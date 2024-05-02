<?php

return [
    /*
     * |--------------------------------------------------------------------------
     * | Validation Language Lines
     * |--------------------------------------------------------------------------
     * |
     * | The following language lines contain the default error messages used by
     * | the validator class. Some of these rules have multiple versions such
     * | as the size rules. Feel free to tweak each of these messages here.
     * |
     */
    'accepted' => ':attribute trebuie să fie acceptat.',
    'active_url' => ':attribute nu este un URL valid.',
    'after' => ':attribute trebuie să fie o dată după :date.',
    'after_or_equal' => ':attribute trebuie să fie o dată după sau egală cu :date.',
    'alpha' => ':attribute poate conține doar litere.',
    'alpha_dash' => ':attribute poate conține doar litere, numere și cratime.',
    'alpha_num' => ':attribute poate conține doar litere și numere.',
    'array' => ':attribute trebuie să fie un array.',
    'before' => ':attribute trebuie să fie o dată înainte de :date.',
    'before_or_equal' => ':attribute trebuie să fie o dată înainte de sau egală cu :date.',
    'between' => [
        'numeric' => ':attribute trebuie să fie între :min și :max.',
        'file' => ':attribute trebuie să fie între :min și :max kilobytes.',
        'string' => ':attribute trebuie să fie între :min și :max caractere.',
        'array' => ':attribute trebuie să aibă între :min și :max items.',
    ],
    'boolean' => ':attribute câmpul trebuie să fie adevărat sau fals.',
    'confirmed' => ':attribute confirmarea nu se potrivește.',
    'date' => ':attribute nu este o dată validă.',
    'date_format' => ':attribute nu se potrivește cu formatul :format.',
    'different' => ':attribute și :other trebuie să fie diferite.',
    'digits' => ':attribute trebuie să aibă :digits cifre.',
    'digits_between' => ':attribute trebuie să aibă între :min și :max cifre.',
    'dimensions' => ':attribute are dimensiuni de imagine nevalide.',
    'distinct' => ':attribute câmpul are o valoare duplicat.',
    'email' => ':attribute trebuie să fie o adresă de e-mail validă.',
    'exists' => ':attribute selectat este invalid.',
    'file' => ':attribute trebuie să fie un fișier.',
    'filled' => ':attribute câmpul este obligatoriu.',
    'image' => ':attribute trebuie să fie o imagine.',
    'in' => ':attribute selectat este invalid.',
    'in_array' => ':attribute câmpul nu există în :other.',
    'integer' => ':attribute trebuie să fie un număr întreg.',
    'ip' => ':attribute trebuie să fie o adresă IP validă.',
    'json' => ':attribute trebuie să fie un șir JSON valid.',
    'max' => [
        'numeric' => ':attribute nu poate fi mai mare de :max.',
        'file' => ':attribute nu poate fi mai mare de :max kilobytes.',
        'string' => ':attribute nu poate fi mai mare de :max caractere.',
        'array' => ':attribute nu poate avea mai mult de :max items.',
    ],
    'mimes' => ':attribute trebuie să fie un fișier de tip: :values.',
    'mimetypes' => ':attribute trebuie să fie un fișier de tip: :values.',
    'min' => [
        'numeric' => ':attribute trebuie să fie cel puțin :min.',
        'file' => ':attribute trebuie să aibă cel puțin :min kilobytes.',
        'string' => ':attribute trebuie să aibă cel puțin :min caractere.',
        'array' => ':attribute trebuie să aibă cel puțin :min items.',
    ],
    'not_in' => ':attribute selectat este invalid.',
    'numeric' => ':attribute trebuie să fie un număr.',
    'present' => ':attribute câmpul trebuie să fie prezent.',
    'regex' => 'Formatul :attribute este invalid.',
    'required' => ':attribute câmpul este obligatoriu.',
    'required_if' => ':attribute câmpul este obligatoriu când :other este :value.',
    'required_unless' => ':attribute câmpul este obligatoriu cu excepția cazului în care :other este în :values.',
    'required_with' => ':attribute câmpul este obligatoriu când :values este prezent.',
    'required_with_all' => ':attribute câmpul este obligatoriu când :values este prezent.',
    'required_without' => ':attribute câmpul este obligatoriu când :values nu este prezent.',
    'required_without_all' => ':attribute câmpul este obligatoriu când niciunul dintre :values nu este prezent.',
    'same' => ':attribute și :other trebuie să se potrivească.',
    'size' => [
        'numeric' => ':attribute trebuie să fie :size.',
        'file' => ':attribute trebuie să fie :size kilobytes.',
        'string' => ':attribute trebuie să fie :size caractere.',
        'array' => ':attribute trebuie să conțină :size items.',
    ],
    'string' => ':attribute trebuie să fie un șir.',
    'timezone' => ':attribute trebuie să fie un fus orar valid.',
    'unique' => ':attribute a fost deja luat.',
    'uploaded' => ':attribute nu a reușit să încarce.',
    'url' => 'Formatul :attribute este invalid.',

    /*
     * |--------------------------------------------------------------------------
     * | Custom Validation Attributes
     * |--------------------------------------------------------------------------
     * |
     * | The following language lines are used to swap attribute place-holders
     * | with something more reader friendly such as E-Mail Address instead
     * | of "email". This simply helps us make messages a little cleaner.
     * |
     */
    'attributes' => [],
    // Internal validation logic for Panel
    'internal' => [
        'variable_value' => ':env variabilă',
        'invalid_password' => 'Parola furnizată a fost invalidă pentru acest cont.',
    ],
];
