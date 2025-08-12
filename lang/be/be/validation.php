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

    'accepted' => 'Необходимо принять :attribute.',
    'active_url' => '{{ attribute }} з\'яўляецца несапраўдным URL-адрасам',
    'after' => 'В поле :attribute должна быть дата после :date.',
    'after_or_equal' => 'В поле :attribute должна быть дата после :date.',
    'alpha' => ':attribute может содержать только буквы.',
    'alpha_dash' => 'Атрибут: может содержать только буквы, цифры и тире.',
    'alpha_num' => ':attribute может содержать только буквы.',
    'array' => 'Необходимо принять :attribute.',
    'before' => 'В поле :attribute должна быть дата после :date.',
    'before_or_equal' => 'В поле :attribute должна быть дата после :date.',
    'between' => [
        'numeric' => 'Значэнне :attribute павінна знаходзіцца ў межах :min і :max',
        'file' => 'Значэнне :attribute павінна знаходзіцца ў межах :min і :max',
        'string' => 'Значэнне :attribute павінна знаходзіцца ў межах :min і :max',
        'array' => 'Значэнне :attribute павінна знаходзіцца ў межах :min і :max',
    ],

    'confirmed' => ':attribute подтверждение не совпадает.',
    'date' => '{{ attribute }} з\'яўляецца несапраўдным URL-адрасам',
    'date_format' => 'Атрибут: не соответствует формату: формат.',
    'different' => ':attribute и :other должны быть разными.',
    'digits' => ':attribute должен содержать :digits цифр.',
    'digits_between' => ':attribute павінен быць паміж :min і :max лічбамі.',
    'dimensions' => ':attribute мае недапушчальныя памеры выявы.',

    'email' => ':attribute павінен быць правільным адрасам пошты.',

    'file' => ':attribute павінен быць файлам.',
    'filled' => 'Поле :attribute абавязковае.',
    'image' => ':attribute павінен быць выявай.',

    'in_array' => 'Поле :attribute не існуе ў :other.',
    'integer' => ':attribute павінен быць цэлым лікам.',
    'ip' => ':attribute павінен быць правільным IP-адрасам.',
    'json' => ':attribute павінен быць правільнай радковай перадачай JSON.',
    'max' => [
        'numeric' => ':attribute не можа перавышаць :max.',
        'file' => ':attribute не можа перавышаць :max кілабайт.',
        'string' => ':attribute не можа перавышаць :max сімвалаў.',
        'array' => ':attribute не можа мець больш за :max элементаў.',
    ],
    'mimes' => ':attribute павінен быць файлам тыпу :values.',
    'mimetypes' => ':attribute павінен быць файлам тыпу :values.',
    'min' => [
        'numeric' => ':attribute павінен быць хаця б :min.',
        'file' => ':attribute павінен быць хаця б :min кілабайт.',
        'string' => ':attribute павінен быць хаця б :min сімвалаў.',
        'array' => ':attribute павінен мець хаця б :min элементаў.',
    ],

    'numeric' => ':attribute павінен быць лікам.',

    'regex' => 'Фармат :attribute недапушчальны.',

    'required_with_all' => 'Поле :attribute абавязковае, калі :values прысутнічае.',

    'same' => ':attribute і :other павінны супадаць.',
    'size' => [
        'numeric' => ':attribute павінен быць :size.',
        'file' => ':attribute павінен быць :size кілабайт.',
        'string' => ':attribute павінен быць :size сімвалаў.',
        'array' => ':attribute павінен утрымліваць :size элементаў.',
    ],
    'string' => ':attribute павінен быць радком.',
    'timezone' => ':attribute павінен быць правільнай зонай.',

    'url' => 'Фармат :attribute недапушчальны.',

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
        'variable_value' => ':env зменная',
        'invalid_password' => 'Уведзены пароль недапушчальны для гэтага акаўнта.',
    ],
];
