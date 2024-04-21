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

    'accepted' => ':attribute musí být přijat.',
    'active_url' => ':attribute není platná URL adresa.',
    'after' => ':attribute musí být po datu :date.',
    'after_or_equal' => ':attribute musí být datum :date nebo pozdější.',
    'alpha' => ':attribute může obsahovat pouze písmena.',
    'alpha_dash' => ':attribute může obsahovat pouze písmena, čísla, a pomlčky.',
    'alpha_num' => ':attribute může obsahovat pouze písmena a čísla.',
    'array' => ':attribute musí být seznam.',
    'before' => ':attribute musí být datum před :date.',
    'before_or_equal' => ':attribute musí být datum před nebo rovné :date.',
    'between' => [
        'numeric' => ':attribute musí být mezi :min a :max.',
        'file' => ':attribute musí být v rozmezí :min a :max kilobajtů.',
        'string' => ':attribute musí mít délku v rozmezí :min a :max znaků.',
        'array' => ':attribute musí mít mezi :min a :max položkami.',
    ],
    'boolean' => ':attribute musí být true nebo false',
    'confirmed' => 'Potvrzení :attribute se neshoduje.',
    'date' => ':attribute není platné datum.',
    'date_format' => ':attribute se neshoduje se správným formátem :format.',
    'different' => ':attribute a :other se musí lišit.',
    'digits' => 'Atribut :attribute musí mít :digits číslic.',
    'digits_between' => ':attribute musí být dlouhé nejméně :min a nejvíce :max číslic.',
    'dimensions' => ':attribute nemá platné rozměry obrázku.',
    'distinct' => ':attribute má duplicitní hodnotu.',
    'email' => ':attribute musí být platná e-mailová adresa.',
    'exists' => 'Vybraný :attribute je neplatný.',
    'file' => ':attribute musí být soubor.',
    'filled' => 'Pole :attribute je povinné.',
    'image' => ':attribute musí být obrázek.',
    'in' => 'Vybraný :attribute je neplatný.',
    'in_array' => 'Pole :attribute neexistuje v :other.',
    'integer' => ':attribute musí být celé číslo.',
    'ip' => ':attribute musí být platná IP adresa.',
    'json' => ':attribute musí být platný řetězec JSON.',
    'max' => [
        'numeric' => ':attribute nemůže být větší než :max.',
        'file' => ':attribute nesmí být větší než :max kilobajtů.',
        'string' => ':attribute nesmí být delší než :max znaků.',
        'array' => ':attribute nesmí obsahovat více než: max položek.',
    ],
    'mimes' => 'Atribut: musí být soubor typu: :values.',
    'mimetypes' => 'Atribut :attribute musí být soubor o typu: :values.',
    'min' => [
        'numeric' => 'Atribut :attribute musí být alepoň :min místný.',
        'file' => 'Atribut :attribute musí mít alapoň :min kilobajtů.',
        'string' => 'Atribut :attribute musí mít alespoň :min znaků.',
        'array' => 'Atribut :attribute musí mít alespoň :min položek.',
    ],
    'not_in' => 'Zvolený atribut :attribute je neplatný.',
    'numeric' => 'Atribut :attribute musí být číslo.',
    'present' => 'Pole atributu :attribute musí být přítomno.',
    'regex' => 'Formát atributu :attribute je neplatný.',
    'required' => 'Pole atributu :attribute je povinné.',
    'required_if' => 'Pole atributu :attribute je povinné když :other je :values.',
    'required_unless' => 'Pole atributu :attribute je povinné pokud není :other :values.',
    'required_with' => 'Pole atributu :attribute je povinné pokud :values je přitomná.',
    'required_with_all' => 'Pole atributu :attribute je povinné pokud :values nejsou přítomny.',
    'required_without' => 'Pole atributu :attribute je povinné pokud :values není přitomna.',
    'required_without_all' => 'Pole atributu :attribute pokud žádná z :values není přítomna.',
    'same' => 'Atribut :attribute a :other se musí shodovat.',
    'size' => [
        'numeric' => 'Atribut :attribute musí být :size místný.',
        'file' => ':attribute musí mít velikost :size Kb.Atribut :attribute musí mít :size kilobajtů.',
        'string' => ':attribute musí mít :size znaků.',
        'array' => ':attribute musí obsahovat :size položek.',
    ],
    'string' => ':attribute musí být text.',
    'timezone' => ':attribute musí být platná zóna.',
    'unique' => ':attribute byl již použit.',
    'uploaded' => 'Nahrávání :attribute se nezdařilo.',
    'url' => 'Formát :attribute není platný.',

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
        'variable_value' => ':env proměnná',
        'invalid_password' => 'Zadané heslo pro tento účet je neplatné.',
    ],
];
