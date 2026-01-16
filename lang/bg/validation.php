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

    'accepted' => ':attribute трябва да се приеме.',
    'active_url' => ':attribute не е валиден линк.',
    'after' => ':attribute трябва да е дата след :date.',
    'after_or_equal' => ':attribute трябва да е дата преди или равна на :date.',
    'alpha' => ':attribute може да съдържа само букви.',
    'alpha_dash' => ':attribute може да съдържа само букви, цифри и тирета.',
    'alpha_num' => ':attribute може да съдържа само букви и цифри.',
    'array' => ':attribute трябва да е масив.',
    'before' => ':attribute трябва да е дата преди :date.',
    'before_or_equal' => ':attribute трябва да е дата преди или след :date.',
    'between' => [
        'numeric' => ':attribute трябва да е между :min и :max.',
        'file' => ':attribute трябва да е между :min и :max килобайта.',
        'string' => ':attribute трябва да е между :min и :max знака.',
        'array' => ':attribute трябва да има между :min и :max елемента.',
    ],

    'confirmed' => 'Потвържденито на :attribute не съвпада.',
    'date' => ':attribute не е валидна дата.',
    'date_format' => ':attribute не съотвества на формата :format.',
    'different' => ':attribute и :other трябва да са различни.',
    'digits' => ':attribute трябва да е :digits цифри.',
    'digits_between' => ':attribute трябва да бъде между :min и :max цифри.',
    'dimensions' => 'Изображението :attribute има невалидни размери.',

    'email' => ':attribute трябва да бъде валиден имейл адрес.',

    'file' => ':attribute трябва да бъде файл.',
    'filled' => 'Полето :attribute е задължително.',
    'image' => ':attribute трябва да е изображение',

    'in_array' => 'Полето :attribute не съществува в :other.',
    'integer' => ':attribute трябва да е цяло число.',
    'ip' => ':attribute трябва да е валиден IP адрес.',
    'json' => ':attribute трябва да е валиден JSON низ.',
    'max' => [
        'numeric' => ':attribute не трябва да е по голям от :max.',
        'file' => ':attribute не трябва да е по-голям от :max килобайта.',
        'string' => ':attribute не трябва да бъде по-дълъг от :max символа.',
        'array' => ':attribute не трябва да има повече от :max елемента.',
    ],
    'mimes' => ':attribute трябва да бъде файл от тип: :values.',
    'mimetypes' => ':attribute трябва да бъде файл от тип: :values.',
    'min' => [
        'numeric' => ':attribute трябва да бъде поне :min.',
        'file' => ':attribute трябва да е поне :min килобайта.',
        'string' => ':attribute трябва да бъде поне :min символа.',
        'array' => ':attribute трябва да има поне :min елемента.',
    ],

    'numeric' => ':attribute трябва да е число.',

    'regex' => 'Формата :attribute е невалиден',

    'required_with_all' => 'Полето :attribute е задължително когато :values присъства.',

    'same' => ':attribute и :other трябва да съвпадат.',
    'size' => [
        'numeric' => ':attribute трябва да е :size.',
        'file' => ':attribute трябвада бъде :size килобайта.',
        'string' => ':attribute трябва да е :size знака.',
        'array' => ':attribute трябва да съдържа :size елемента.',
    ],
    'string' => ':attribute трябва да бъде низ.',
    'timezone' => ':attribute трябва да бъде валидна зона.',

    'url' => 'Формата :attribute е невалиден.',

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
        'variable_value' => ':env променливо',
        'invalid_password' => 'Зададената парола е невалидна за този акаунт.',
    ],
];
