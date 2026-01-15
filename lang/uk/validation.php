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

    'accepted' => ':attribute повинен бути прийнятий.',
    'active_url' => ':attribute не є дійсним URL.',
    'after' => ':attribute повинен бути датою після :date.',
    'after_or_equal' => ':attribute повинен бути датою після або рівною :date.',
    'alpha' => ':attribute може містити лише літери.',
    'alpha_dash' => ':attribute може містити лише літери, цифри та дефіси.',
    'alpha_num' => ':attribute може містити лише літери та цифри.',
    'array' => ':attribute повинен бути масивом.',
    'before' => ':attribute повинен бути датою до :date.',
    'before_or_equal' => ':attribute повинен бути датою до або рівною :date.',
    'between' => [
        'numeric' => ':attribute повинен бути між :min та :max.',
        'file' => ':attribute повинен бути між :min та :max кілобайтами.',
        'string' => ':attribute повинен містити від :min до :max символів.',
        'array' => ':attribute повинен містити від :min до :max елементів.',
    ],

    'confirmed' => 'Підтвердження :attribute не співпадає.',
    'date' => ':attribute не є дійсною датою.',
    'date_format' => ':attribute не відповідає формату :format.',
    'different' => ':attribute та :other повинні відрізнятися.',
    'digits' => ':attribute повинен містити :digits цифр.',
    'digits_between' => ':attribute повинен містити від :min до :max цифр.',
    'dimensions' => ':attribute має недійсні розміри зображення.',

    'email' => ':attribute повинен бути дійсною електронною адресою.',

    'file' => ':attribute повинен бути файлом.',
    'filled' => 'Поле :attribute є обов’язковим.',
    'image' => ':attribute повинен бути зображенням.',

    'in_array' => 'Поле :attribute не існує в :other.',
    'integer' => ':attribute повинен бути цілим числом.',
    'ip' => ':attribute повинен бути дійсною IP-адресою.',
    'json' => ':attribute повинен бути дійсним JSON-рядком.',
    'max' => [
        'numeric' => ':attribute не може бути більше ніж :max.',
        'file' => ':attribute не може перевищувати :max кілобайтів.',
        'string' => ':attribute не може містити більше ніж :max символів.',
        'array' => ':attribute не може містити більше ніж :max елементів.',
    ],
    'mimes' => ':attribute повинен бути файлом типу: :values.',
    'mimetypes' => ':attribute повинен бути файлом типу: :values.',
    'min' => [
        'numeric' => ':attribute повинен бути не менше ніж :min.',
        'file' => ':attribute повинен бути не менше ніж :min кілобайтів.',
        'string' => ':attribute повинен містити принаймні :min символів.',
        'array' => ':attribute повинен містити принаймні :min елементів.',
    ],

    'numeric' => ':attribute повинен бути числом.',

    'regex' => 'Формат :attribute є недійсним.',

    'required_with_all' => 'Поле :attribute є обов’язковим, коли присутні :values.',

    'same' => ':attribute та :other повинні збігатися.',
    'size' => [
        'numeric' => ':attribute повинен бути :size.',
        'file' => ':attribute повинен бути :size кілобайтів.',
        'string' => ':attribute повинен містити :size символів.',
        'array' => ':attribute повинен містити :size елементів.',
    ],
    'string' => ':attribute повинен бути рядком.',
    'timezone' => ':attribute повинен бути дійсною часовою зоною.',

    'url' => 'Формат :attribute є недійсним.',

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
        'variable_value' => 'Змінна :env',
        'invalid_password' => 'Наданий пароль є недійсним для цього облікового запису.',
    ],
];
